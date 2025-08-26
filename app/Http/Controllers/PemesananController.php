<?php

namespace App\Http\Controllers;

use App\Models\Rute;
use App\Models\Category;
use App\Models\Pemesanan;
use App\Models\Transportasi;
use App\Models\Pemesanan_Detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailNotification; // Assuming you have a Mailable class defined for the email notification
use Illuminate\Support\Facades\DB;

class PemesananController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_backup()
    {
        $ruteAwal = Rute::orderBy('start')->get()->groupBy('start');
        if (count($ruteAwal) > 0) {
            foreach ($ruteAwal as $key => $value) {
                $data['start'][] = $key;
            }
        } else {
            $data['start'] = [];
        }
        $ruteAkhir = Rute::orderBy('end')->get()->groupBy('end');
        if (count($ruteAkhir) > 0) {
            foreach ($ruteAkhir as $key => $value) {
                $data['end'][] = $key;
            }
        } else {
            $data['end'] = [];
        }
        $category = Category::orderBy('name')->get();
        return view('client.index', compact('data', 'category'));
    }

    public function index()
    {
        // Group all routes by 'start' location
        $ruteAwal = Rute::orderBy('start')->get()->groupBy('start');
        if (count($ruteAwal) > 0) {
            foreach ($ruteAwal as $key => $value) {
                $data['start'][] = $key;
            }
        } else {
            $data['start'] = [];
        }

        // Group all routes by 'end' location
        $ruteAkhir = Rute::orderBy('end')->get()->groupBy('end');
        if (count($ruteAkhir) > 0) {
            foreach ($ruteAkhir as $key => $value) {
                $data['end'][] = $key;
            }
        } else {
            $data['end'] = [];
        }

        // Get all categories
        $category = Category::orderBy('name')->get();

        // Return the Penumpang view
        return view('client.index', compact('data', 'category'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->category) {
            $category = Category::find($request->category);
            $data = [
                'start' => $request->start,
                'end' => $request->end,
                'category' => $category->id,
                'waktu' => $request->waktu,
            ];
            $data = Crypt::encrypt($data);
            return redirect()->route('show', ['id' => $category->slug, 'data' => $data]);
        } else {
            $this->validate($request, [
                'rute_id' => 'required',
                'waktu' => 'required',
            ]);

            $huruf = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
            $kodePemesanan = strtoupper(substr(str_shuffle($huruf), 0, 7));

            $rute = Rute::with('transportasi.category')->get();
            // $jumlah_kursi = $rute->transportasi->jumlah + 2;
            // $kursi = (int) floor($jumlah_kursi / 5);
            // $kode = "ABCDE";
            // $kodeKursi = strtoupper(substr(str_shuffle($kode), 0, 1) . rand(1, $kursi));

            $waktu = $request->waktu . " " . $rute->jam;

            Pemesanan::Create([
                'kode' => $kodePemesanan,
                // 'kursi' => $request,
                'waktu' => $waktu,
                'total' => $rute->harga,
                'rute_id' => $rute->id,
                'penumpang_id' => Auth::user()->id
            ]);

            return redirect()->back()->with('success', 'Pemesanan Tiket ' . $rute->transportasi->category->name . ' Success!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function _show($id, $data)
    {
        $data = Crypt::decrypt($data);
        $category = Category::find($data['category']);
        
        $rute = Rute::with('transportasi')->get();
        if ($rute->count() > 0) {
            foreach ($rute as $val) {
                $pemesanan = Pemesanan::where('rute_id', $val->id)->count();
                if ($val->transportasi) {
                    $kursi = Transportasi::find($val->transportasi_id)->jumlah - $pemesanan;
                    if ($val->transportasi->category_id == $category->id) {
                        $dataRute[] = [
                            'harga' => $val->harga,
                            'start' => $val->start,
                            'end' => $val->end,
                            'tujuan' => $val->tujuan,
                            'transportasi' => $val->transportasi->name,
                            'kode' => $val->transportasi->kode,
                            'kursi' => $kursi, // Ensure $kursi is included in the data
                            'waktu' => date("h:i A", strtotime($val->jam)),
                            'id' => $val->id,
                            'kategori' => $category->name
                        ];
                    }
                }
            }
            sort($dataRute);
        } else {
            $dataRute = [];
        }
        
        $id = $category->name;
        return view('client.show', compact('id', 'dataRute', 'kursi')); // Pass $kursi to the view
    }

     
    public function show($id, $data)
    {
        // Decrypt the data
        $data = Crypt::decrypt($data);

        // Find the category based on the decrypted data
        $category = Category::find($data['category']);
        
        // Fetch relevant route data based on the category
        $rute = Rute::with('transportasi')->get();

        // Initialize an empty array to store route data
        $dataRute = [];

        // Iterate over route data and filter based on category
        foreach ($rute as $val) {
            //$pemesanan = Pemesanan::where('rute_id', $val->id)->count();
            //Blok semua pesanan yang sudah lunas dan masih dalam verifikasi pembayaran
            $pemesanan = Pemesanan::where('rute_id', $val->id)
                                    ->where(function ($query) {
                                        $query->where('status', 'Sudah Bayar')
                                            ->orWhere('status_pembayaran', 'Menunggu Verifikasi');
                                    })
                                    ->where('rowstatus','>=',0)
                                    ->sum('kursi');
            //Blok semua seat yang belum bayar tapi masih dalam rentang waktu pembayaran
            $pemesanan_pending = Pemesanan::where('rute_id', $val->id)
                                            ->where('status', 'Belum Bayar')
                                            ->where('rowstatus','>=',0)
                                            ->where('isFisik', '0')
                                            ->where(function ($query) {
                                                $query->where('expired_date', '>', now())
                                                      ->orWhereNull('expired_date');
                                            })
                                            //->where('isChurch','!=', '1')
                                            ->sum('kursi');
            if ($val->transportasi && $val->transportasi->category_id == $category->id) {
                $kursi = Transportasi::find($val->transportasi_id)->jumlah - $pemesanan - $pemesanan_pending;
                $dataRute[] = [
                    'harga' => $val->harga,
                    'start' => $val->start,
                    'end' => $val->end,
                    'tujuan' => $val->tujuan,
                    'transportasi' => $val->transportasi->name,
                    'kode' => $val->transportasi->kode,
                    'kursi' => $kursi,
                    'waktu' => date("h:i A", strtotime($val->jam)),
                    'event_date' => date("h:i A", strtotime($val->jam)),
                    'id' => $val->id,
                    'kategori' => $category->name,
                    'isForAdmin' => $val->transportasi->isForAdmin
                ];
            }
        }
        
        // Sort the data if needed
        sort($dataRute);
        
        // Encode the decrypted data as a JSON string
        $dataString = json_encode($data);

        // Pass the necessary variables to the view
        return view('client.show', compact('id', 'dataRute', 'dataString'));
    }

    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Crypt::decrypt($id);
        $rute = Rute::find($data['id']);
        $transportasi = Transportasi::find($rute->transportasi_id);
        //$pesananDetail = Pemesanan_Detail::where('pemesananCode', 'LIKE', '%' . $data['kode'] . '%')->get();
        $dataString = json_encode($data);
        return view('client.kursi', compact('data', 'transportasi', 'dataString'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function __pesan($kursi, $data)
    {
        $d = Crypt::decrypt($data);
        $huruf = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $kodePemesanan = strtoupper(substr(str_shuffle($huruf), 0, 7));

        $rute = Rute::with('transportasi.category')->find($d['id']);

        $waktu = Carbon::parse($d['waktu'])->format('Y-m-d') . ' ' . $rute->jam;

        Pemesanan::Create([
            'kode' => $kodePemesanan,
            'kursi' => $kursi,
            'waktu' => $waktu,
            'total' => $rute->harga,
            'rute_id' => $rute->id,
            'penumpang_id' => Auth::user()->id
        ]);

        //return redirect('/')->with('success', 'Pemesanan Tiket ' . $rute->transportasi->category->name . ' Success!');
        return redirect('/transaksi/'.$kodePemesanan)->with('success', 'Pemesanan Tiket ' . $rute->transportasi->category->name . ' Success!');
    }

    public function pesan__BACKUP26AUGUST25 ($kursi, $encodedData, $referral = null)
    {
        if (is_string($kursi) && substr($kursi, 0, 1) === '[') {
            $kursiArray = json_decode($kursi, true);
            $seatCount = is_array($kursiArray) ? count($kursiArray) : 0;
        } else {
            $seatCount = (int)$kursi;
        }
        
        if ($seatCount > 5 && $auth()->user()->level == 'Penumpang') {
            Log::info('Pemesanan Melebihi Batas');
            return redirect()->route('store')->with('error', 'Pemesanan melebihi batas maksimal 5 tiket');
        }

        // Decrypt the data
        //$data = Crypt::decrypt($encodedData);
        try {
        $data = Crypt::decrypt($encodedData);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            $data = json_decode(urldecode($encodedData), true);
        }

        // Get the route details
        $rute = Rute::with('transportasi.category')->find($data['id']);

        // Calculate the total price
        //$total = $rute->harga * $kursi;
        $total = $rute->harga * $seatCount;

        // Generate a random booking code
        $huruf = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $kodePemesanan = strtoupper(substr(str_shuffle($huruf), 0, 7));

        try {
            // Start a transaction
            DB::beginTransaction();

            // GENERATE PURCHASE
            if(auth()->user()->level != 'Penumpang'){
                Pemesanan::create([
                'kode' => $kodePemesanan,
                'kursi' => $kursi,
                'waktu' => Carbon::parse($data['waktu'])->format('Y-m-d') . ' ' . $rute->jam,
                'total' => $total,
                'rute_id' => $rute->id,
                'penumpang_id' => Auth::user()->id,
                'petugas_id' => Auth::user()->id,
                'status' => 'Sudah Bayar',
                'referral' => $referral, // Insert referral value into the database
                'expired_date' => Carbon::now(), // Set expired_date to today + 3 days
                'rowstatus' => 0,
                'isChurch' => false,
                'isFisik' => false,
            ]);   
            }
            else{
                Pemesanan::create([
                'kode' => $kodePemesanan,
                'kursi' => $kursi,
                'waktu' => Carbon::parse($data['waktu'])->format('Y-m-d') . ' ' . $rute->jam,
                'total' => $total,
                'rute_id' => $rute->id,
                'penumpang_id' => Auth::user()->id,
                'referral' => $referral, // Insert referral value into the database
                'expired_date' => Carbon::now()->addDays(3), // Set expired_date to today + 3 days
                'rowstatus' => 0,
                'isChurch' => false,
                'isFisik' => false,
            ]);   
            } 

            foreach ($kursiArray as $seatNumber) {
                Pemesanan_Detail::create([
                    'pemesananCode' => $kodePemesanan,
                    'seatNumber'    => $seatNumber,
                    'isCheckedIn'   => 0,
                ]);
            }

            // Commit the transaction
            DB::commit();

        $message_blank = '[NOTIFIKASI VOS]';

        // Send admin WhatsApp message
        // WA si Admin
        error_log(env('APP_ENV'));
        if (env('APP_ENV') != 'production') {
            $destinationAdmin = '6285156651097'; // dev
            // Send WhatsApp message
            $destination = Auth::user()->username;
            $message = '[NOTIFIKASI VOS DEVELOPMENT] Pesanan tiket konser VOS Interval | Pre Competition Concert, 20 Juli 2024 dengan kode booking: ' . $kodePemesanan . ' telah diterima. 
    Mohon segera melakukan pembayaran tiket ke rekening: 
    BCA 3420184785 a.n Ratno Juniarto MS 
    dengan nominal : ' . $total . '
    bukti transfer dapat dikirim melalui website e-Ticket VOS 

    Pesanan anda dapat dilacak melalui ' . url('/transaksi/' . $kodePemesanan) . '  dengan login: 
    Username : ' . Auth::user()->username . ' 
    Password : password12345678

    CS VOS (http://wa.me/6285823536364 atau http://wa.me/6287780553668)';

            $messageAdmin = '[NOTIFIKASI VOS DEVELOPMENT] Tabea.! Pesanan baru dengan kode pesanan ' . $kodePemesanan . ' sudah diterima. Mohon segera dikonfirmasi!
    Nomor Kontak Pembeli : https://wa.me/' . Auth::user()->username . '';
            //$responseAdmin = $this->sendWhatsAppMessage_2($destinationAdmin, $messageAdmin);
        } else {
            //!!! PRODUCTION !!!
            $destinationAdmin = '6285823536364'; // jean
            $destinationAdmin2 = '6287780553668'; // tiara
            // Send WhatsApp message
            $destination = Auth::user()->username;
            $message = '[NOTIFIKASI VOS] Pesanan tiket konser VOS Interval | Pre Competition Concert, 20 Juli 2024 dengan kode booking: ' . $kodePemesanan . ' telah diterima. 
    Mohon segera melakukan pembayaran tiket ke rekening: 
    BCA 3420184785 a.n Ratno Juniarto MS 
    dengan nominal : ' . $total . '
    bukti transfer dapat dikirim melalui website e-Ticket VOS 

    Pesanan anda dapat dilacak melalui ' . url('/transaksi/' . $kodePemesanan) . ' dengan login: 
    Username : ' . Auth::user()->username . ' 
    Password : password12345678

    CS VOS (http://wa.me/6285823536364 atau http://wa.me/6287780553668)';

            $messageAdmin = '[NOTIFIKASI VOS] Tabea.! Pesanan baru dengan kode pesanan ' . $kodePemesanan . ' sudah diterima. Mohon segera dikonfirmasi!
    Nomor Kontak Pembeli : https://wa.me/' . Auth::user()->username . '';
            //$responseAdmin = $this->sendWhatsAppMessage_2($destinationAdmin, $messageAdmin);
            //$responseAdmin2 = $this->sendWhatsAppMessage_2($destinationAdmin2, $messageAdmin);
        }

        //$response = $this->sendWhatsAppMessage_2($destination, $message);

        // kirim WA Template
        //$this->sendWhatsAppMessage_pesanSuccess($destination, $message_blank, $kodePemesanan);

        // Send email
        $emailData = [
            'subject' => '[VOS] Pesanan Tiket Konser VOS anda telah berhasil - Kode Booking : ' . $kodePemesanan,
            'content' => $message // You can customize the email content as per your requirements
        ];
        Mail::to(Auth::user()->email)->send(new EmailNotification($emailData));

        // Send email admin
        $emailDataAdmin = [
            'subject' => '[VOS] Pesanan Masuk - Kode Booking : ' . $kodePemesanan,
            'content' => $messageAdmin // You can customize the email content as per your requirements
        ];
        if (env('APP_ENV') == 'production') {
            //Mail::to("jeansengkey10@gmail.com")->send(new EmailNotification($emailDataAdmin)); // jean
            //Mail::to("jen.tenmury@gmail.com")->send(new EmailNotification($emailDataAdmin)); // tiara
        }
        else{
            Mail::to("axcellentwalukow@gmail.com")->send(new EmailNotification($emailDataAdmin));
        }
        Mail::to("cs@voiceofsoulchoir.id")->send(new EmailNotification($emailData)); // cs

        } catch (\Exception $e) {
            DB::rollBack();

            // $messageAdmin = '[NOTIFIKASI VOS] ERROR! Modul : Pemesanan';
            // $destinationAdmin = '6285156651097'; 
            // $responseAdmin = $this->sendWhatsAppMessage_2($destinationAdmin, $messageAdmin);

            // Log the error
            Log::error('Error creating Pemesanan: ' . $e->getMessage());
            return redirect()->route('store')->with('error', 'Terjadi kesalahan saat memproses pemesanan. Silakan coba beberapa saat lagi.');
            // For debugging only — don't use in production
            /*return response()->make(
                '<h1>Error Creating Pemesanan</h1>' .
                '<p><strong>Message:</strong> ' . e($e->getMessage()) . '</p>' .
                '<p><strong>File:</strong> ' . e($e->getFile()) . '</p>' .
                '<p><strong>Line:</strong> ' . e($e->getLine()) . '</p>' .
                '<pre>' . e($e->getTraceAsString()) . '</pre>',
                500
            );
            */
        }
        // Redirect to the transaction page with success message
        return redirect('/transaksi/' . $kodePemesanan)->with('success', 'Pemesanan Tiket ' . $rute->transportasi->category->name . ' Success!');
    }

    public function pesan($kursi, $encodedData, $referral = null)
{
    if (is_string($kursi) && substr($kursi, 0, 1) === '[') {
        $kursiArray = json_decode($kursi, true);
        $seatCount = is_array($kursiArray) ? count($kursiArray) : 0;
    } else {
        $seatCount = (int)$kursi;
    }
    
    if ($seatCount > 5 && auth()->user()->level == 'Penumpang') {
        Log::info('Pemesanan Melebihi Batas');
        return redirect()->route('store')->with('error', 'Pemesanan melebihi batas maksimal 5 tiket');
    }

    // Decrypt the data
    try {
        $data = Crypt::decrypt($encodedData);
    } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
        $data = json_decode(urldecode($encodedData), true);
    }

    // Get the route details
    $rute = Rute::with('transportasi.category')->find($data['id']);

    // Calculate the total price
    $total = $rute->harga * $seatCount;

    // Generate a random booking code
    $huruf = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    $kodePemesanan = strtoupper(substr(str_shuffle($huruf), 0, 7));

    try {
        // Start a transaction
        DB::beginTransaction();

        // GENERATE PURCHASE
        if(auth()->user()->level != 'Penumpang'){
            Pemesanan::create([
                'kode' => $kodePemesanan,
                'kursi' => $kursi,
                'waktu' => Carbon::parse($data['waktu'])->format('Y-m-d') . ' ' . $rute->jam,
                'total' => $total,
                'rute_id' => $rute->id,
                'penumpang_id' => Auth::user()->id,
                'petugas_id' => Auth::user()->id,
                'status' => 'Sudah Bayar',
                'referral' => $referral,
                'expired_date' => Carbon::now(),
                'rowstatus' => 0,
                'isChurch' => false,
                'isFisik' => false,
            ]);   
        }
        else{
            Pemesanan::create([
                'kode' => $kodePemesanan,
                'kursi' => $kursi,
                'waktu' => Carbon::parse($data['waktu'])->format('Y-m-d') . ' ' . $rute->jam,
                'total' => $total,
                'rute_id' => $rute->id,
                'penumpang_id' => Auth::user()->id,
                'referral' => $referral,
                'expired_date' => Carbon::now()->addDays(3),
                'rowstatus' => 0,
                'isChurch' => false,
                'isFisik' => false,
            ]);   
        } 

        // Create seat details if kursiArray exists
        if (isset($kursiArray) && is_array($kursiArray)) {
            foreach ($kursiArray as $seatNumber) {
                Pemesanan_Detail::create([
                    'pemesananCode' => $kodePemesanan,
                    'seatNumber'    => $seatNumber,
                    'isCheckedIn'   => 0,
                ]);
            }
        }

        // Commit the transaction
        DB::commit();

        // Prepare email data for user
        $userEmailData = [
            'bookingCode' => $kodePemesanan,
            'eventDate' => Carbon::parse($data['waktu'])->format('d F Y'),
            'eventTime' => $rute->jam,
            'seats' => is_array($kursiArray) ? implode(', ', $kursiArray) : $seatCount . ' seat(s)',
            'totalAmount' => 'Rp ' . number_format($total, 0, ',', '.'),
            'paymentUrl' => url('/transaksi/' . $kodePemesanan),
            'transactionUrl' => url('/transaksi/' . $kodePemesanan),
            'helpCenterUrl' => url('/help'),
            'termsUrl' => url('/terms'),
            'privacyUrl' => url('/privacy'),
        ];

        // Prepare email data for admin
        $adminEmailData = [
            'subject' => '[VOS] Pesanan Masuk - Kode Booking : ' . $kodePemesanan,
            'content' => '[NOTIFIKASI VOS] Tabea.! Pesanan baru dengan kode pesanan ' . $kodePemesanan . ' sudah diterima. Mohon segera dikonfirmasi! Nomor Kontak Pembeli : ' . Auth::user()->username
        ];

        // Send emails
        Mail::to(Auth::user()->email)->send(new EmailNotification($userEmailData));

        // Send admin email based on environment
        if (env('APP_ENV') == 'production') {
            // Mail::to("jeansengkey10@gmail.com")->send(new EmailNotification($adminEmailData));
            // Mail::to("jen.tenmury@gmail.com")->send(new EmailNotification($adminEmailData));
        } else {
            Mail::to("axcellentwalukow@gmail.com")->send(new EmailNotification($adminEmailData));
        }
        
        Mail::to("cs@voiceofsoulchoir.id")->send(new EmailNotification($userEmailData));

        // Send WhatsApp notifications
        if (env('APP_ENV') != 'production') {
            $destinationAdmin = '6285156651097';
            $destination = Auth::user()->username;
            $message = '[NOTIFIKASI VOS DEVELOPMENT] Pesanan tiket konser VOS Interval | Pre Competition Concert, 20 Juli 2024 dengan kode booking: ' . $kodePemesanan . ' telah diterima. 
            Mohon segera melakukan pembayaran tiket ke rekening: 
            BCA 3420184785 a.n Ratno Juniarto MS 
            dengan nominal : ' . $total . '
            bukti transfer dapat dikirim melalui website e-Ticket VOS 
            
            Pesanan anda dapat dilacak melalui ' . url('/transaksi/' . $kodePemesanan) . '  dengan login: 
            Username : ' . Auth::user()->username . ' 
            Password : password12345678
            
            CS VOS (http://wa.me/6285823536364 atau http://wa.me/6287780553668)';
            
            $messageAdmin = '[NOTIFIKASI VOS DEVELOPMENT] Tabea.! Pesanan baru dengan kode pesanan ' . $kodePemesanan . ' sudah diterima. Mohon segera dikonfirmasi!
            Nomor Kontak Pembeli : https://wa.me/' . Auth::user()->username;
        } else {
            $destinationAdmin = '6285823536364';
            $destinationAdmin2 = '6287780553668';
            $destination = Auth::user()->username;
            $message = '[NOTIFIKASI VOS] Pesanan tiket konser VOS Interval | Pre Competition Concert, 20 Juli 2024 dengan kode booking: ' . $kodePemesanan . ' telah diterima. 
            Mohon segera melakukan pembayaran tiket ke rekening: 
            BCA 3420184785 a.n Ratno Juniarto MS 
            dengan nominal : ' . $total . '
            bukti transfer dapat dikirim melalui website e-Ticket VOS 
            
            Pesanan anda dapat dilacak melalui ' . url('/transaksi/' . $kodePemesanan) . ' dengan login: 
            Username : ' . Auth::user()->username . ' 
            Password : password12345678
            
            CS VOS (http://wa.me/6285823536364 atau http://wa.me/6287780553668)';
            
            $messageAdmin = '[NOTIFIKASI VOS] Tabea.! Pesanan baru dengan kode pesanan ' . $kodePemesanan . ' sudah diterima. Mohon segera dikonfirmasi!
            Nomor Kontak Pembeli : https://wa.me/' . Auth::user()->username;
        }

        // $this->sendWhatsAppMessage_2($destination, $message);
        // $this->sendWhatsAppMessage_2($destinationAdmin, $messageAdmin);
        // if (env('APP_ENV') == 'production') {
        //     $this->sendWhatsAppMessage_2($destinationAdmin2, $messageAdmin);
        // }
        // $this->sendWhatsAppMessage_pesanSuccess($destination, '[NOTIFIKASI VOS]', $kodePemesanan);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error creating Pemesanan: ' . $e->getMessage());
        return redirect()->route('store')->with('error', 'Terjadi kesalahan saat memproses pemesanan. Silakan coba beberapa saat lagi.');
    }
    
    // Redirect to the transaction page with success message
    return redirect('/transaksi/' . $kodePemesanan)->with('success', 'Pemesanan Tiket ' . $rute->transportasi->category->name . ' Success!');
}

    public function sendWhatsAppMessage_2($destination, $message)
    {
        $url = 'https://wa.srv34.wapanels.com/send-message';
        //$url   = 'https://api.watsap.id/send-message';
        $apiKey = '5307c9fcda1ebd5e834ecde69ea16da70ee4d104'; // Insert your API key here
        $id_device = '7601';
    
        $data = [
            'api_key' => $apiKey,
            'sender' => '6285781788462',
            'number' => $destination,
            'message' => $message
        ];

        $data_post = [
            'id_device' => $id_device,
            'api-key' => $apiKey,
            'no_hp'   => '6285781788462',
            'pesan'   => $message
         ];
    
        $curl = curl_init();
    
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            //CURLOPT_POSTFIELDS => json_encode($data_post),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
    
        $response = curl_exec($curl);
    
        curl_close($curl);
    
        return $response;
    }

    public function sendWhatsAppMessage_pesanSuccess($destination, $message, $kode)
    {
        $url = 'https://wa.srv34.wapanels.com/send-template';
        $apiKey = '5307c9fcda1ebd5e834ecde69ea16da70ee4d104'; // Insert your API key here
    
        $data = [
            'sender' => '6285781788462',
            'api_key' => $apiKey,
            'number' => $destination,
            'url' => null,
            'footer' => 'Link konfirmasi pembelian tiket',
            'message' => $message,
            'template' => ["call|Telepon CS VOS|081257575617","url|WA CS VOS|https://api.whatsapp.com/send?phone=6285156651097&text=Halo%20Admin%2C%20saya%20sudah%20melakukan%20pembelian%20tiket%20konser%20dengan%20kode%3A%20{{ $kode }}%20%5BBukti%20Bayar%20Dilampirkan%5D"]
        ];
    
        $curl = curl_init();
    
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
    
        $response = curl_exec($curl);
    
        curl_close($curl);
    
        return $response;
    }


}
