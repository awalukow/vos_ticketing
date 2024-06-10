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
    public function index()
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
            $pemesanan = Pemesanan::where('rute_id', $val->id)
                                    ->where(function ($query) {
                                        $query->where('status', 'like', 'Sudah Dibayar')
                                            ->orWhere('status_pembayaran', 'like', 'Menunggu Verifikasi');
                                    })
                                    ->where('rowstatus', '>=', 0)
                                    ->sum('kursi');
            if ($val->transportasi && $val->transportasi->category_id == $category->id) {
                $kursi = Transportasi::find($val->transportasi_id)->jumlah - $pemesanan;
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
                    'kategori' => $category->name
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

    public function pesan_BACKUP270424($kursi, $data)
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

    public function pesan_NOUSE($data)
    {
        $d = Crypt::decrypt($data);
        $huruf = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $kodePemesanan = strtoupper(substr(str_shuffle($huruf), 0, 7));

        $rute = Rute::with('transportasi.category')->find($d['id']);

        // Selecting a default seat (e.g., the first available seat)
        $firstAvailableSeat = $rute->pemesanans()->count() + 1; // Assuming seats are numbered sequentially

        $waktu = Carbon::parse($d['waktu'])->format('Y-m-d') . ' ' . $rute->jam;

        Pemesanan::create([
            'kode' => $kodePemesanan,
            'kursi' => 'K' . $firstAvailableSeat, // Setting default seat
            'waktu' => $waktu,
            'total' => $rute->harga,
            'rute_id' => $rute->id,
            'penumpang_id' => Auth::user()->id
        ]);

        return redirect('/transaksi/'.$kodePemesanan)->with('success', 'Pemesanan Tiket ' . $rute->transportasi->category->name . ' Success!');
    }

    public function pesan_tempChange($kursi, $data)
    {
        //$d = Crypt::decrypt($data);
        $dataKursi = json_decode($kursi, true); 
        $dataArray = json_decode($data, true); 
        $huruf = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        
        $rute = Rute::with('transportasi.category')->find($dataArray['id']);
        $waktu = Carbon::parse($dataArray['waktu'])->format('Y-m-d') . ' ' . $rute->jam;

        $kodePemesanan = strtoupper(substr(str_shuffle($huruf), 0, 7));
        //$checkSeat = Pemesanan::with('kode')->find($kodePemesanan);
        //$checkSeat = Pemesanan::with('kode')->where('kode', 'LIKE', $kodePemesanan)->get();
        $checkSeat = Pemesanan_Detail::with('pemesananCode')->where('pemesananCode', 'LIKE', $kodePemesanan)->get();
        
        if ($checkSeat != null || $checkSeat != "") {
            $temp_kursi = "";
            $count = 0; 
            $total_elements = count($dataKursi); 
            
            foreach ($dataKursi as $a){
                $count++;
                if ($count < $total_elements) {
                    $temp_kursi .= $a . ", "; 
                } else {
                    $temp_kursi .= $a; 
                }
            }
            $harga = $rute->harga * $count;
            Pemesanan::create([
                'kode' => $kodePemesanan,
                'kursi' => $temp_kursi,
                'waktu' => $waktu,
                //'total' => $rute->harga,
                'total' => $harga,
                'rute_id' => $rute->id,
                'penumpang_id' => Auth::user()->id
            ]);

            foreach ($dataKursi as $k) {
                Pemesanan_Detail::create([
                    'pemesananCode' => $kodePemesanan,
                    'seatNumber' => $k,
                    'category_id' => $rute->transportasi->category->id
                ]);
            }

            // Define $destination and $message for WA
            $destination = Auth::user()->username; 
            $message = '[NOTIFIKASI VOS] Pesanan tiket konser VOS Pre Competition Concert, 06 Juli 2024 dengan kode booking: ' . $kodePemesanan . ' telah diterima. 
Mohon segera mengirimkan bukti transfer ke CS VOS (http://wa.me/6285156651097) 

Pesanan anda dapat dilacak melalui http://dev-ticketing.voiceofsoulchoirindonesia.com/transaksi/'.$kodePemesanan.' dengan login: 
Username : '.Auth::user()->username.' 
Password : password12345678'; 
            $message_blank = '[NOTIFIKASI VOS]';

            // Call sendSMS method
            $responseWA_2 = $this->sendWhatsAppMessage_2($destination, $message);
            //sleep(3); // Add a 3-second delay
            $response = $this->sendWhatsAppMessage_pesanSuccess($destination, $message_blank, $kodePemesanan);

            
            return redirect('/transaksi/'.$kodePemesanan)->with('success', 'Pemesanan Tiket ' . $rute->transportasi->category->name . ' Success!');
        } else {
            Log::info('Pemesanan dengan kode ' . $kodePemesanan . ' sudah ada.');
            return redirect()->route('store')->with('error', 'Pemesanan dengan kode ' . $kodePemesanan . ' sudah ada.');
        }
    }

    public function encryptData(Request $request)
{
    return Crypt::encrypt($request->data);
}

public function pesan($kursi, $encodedData, $referral = null)
{
    if ($kursi > 5) {
        Log::info('Pemesanan Melebihi Batas');
        return redirect()->route('store')->with('error', 'Pemesanan melebihi batas maksimal 5 tiket');
    }

    // Decrypt the data
    $data = Crypt::decrypt($encodedData);

    // Get the route details
    $rute = Rute::with('transportasi.category')->find($data['id']);

    // Calculate the total price
    $total = $rute->harga * $kursi;

    // Generate a random booking code
    $huruf = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    $kodePemesanan = strtoupper(substr(str_shuffle($huruf), 0, 7));

    try {
        // Start a transaction
        DB::beginTransaction();

        // GENERATE PURCHASE
        Pemesanan::create([
            'kode' => $kodePemesanan,
            'kursi' => $kursi,
            'waktu' => Carbon::parse($data['waktu'])->format('Y-m-d') . ' ' . $rute->jam,
            'total' => $total,
            'rute_id' => $rute->id,
            'penumpang_id' => Auth::user()->id,
            'referral' => $referral, // Insert referral value into the database
            'expired_date' => Carbon::now()->addDays(3), // Set expired_date to today + 3 days
            'rowstatus' => 0
        ]);        

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
    if (env('APP_ENV') != 'production') {
        Mail::to("jeansengkey10@gmail.com")->send(new EmailNotification($emailDataAdmin)); // jean
        Mail::to("jen.tenmury@gmail.com")->send(new EmailNotification($emailDataAdmin)); // tiara
    }
    Mail::to("cs@voiceofsoulchoirindonesia.com")->send(new EmailNotification($emailData)); // cs

    } catch (\Exception $e) {
        DB::rollBack();

        // $messageAdmin = '[NOTIFIKASI VOS] ERROR! Modul : Pemesanan';
        // $destinationAdmin = '6285156651097'; 
        // $responseAdmin = $this->sendWhatsAppMessage_2($destinationAdmin, $messageAdmin);

        // Log the error
        Log::error('Error creating Pemesanan: ' . $e->getMessage());
        return redirect()->route('store')->with('error', 'Terjadi kesalahan saat memproses pemesanan. Silakan coba beberapa saat lagi.');
    }
    // Redirect to the transaction page with success message
    return redirect('/transaksi/' . $kodePemesanan)->with('success', 'Pemesanan Tiket ' . $rute->transportasi->category->name . ' Success!');
}



public function pesan__CANCELEDAGAIN($kursi, $encodedData)
{
    $dataArray = json_decode(Crypt::decrypt($encodedData), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return redirect()->route('store')->with('error', 'Invalid data provided.');
    }

    $requestedSeatCount = $kursi;

    // Get the rute and transportasi details
    $rute = Rute::with('transportasi.category')->find($dataArray['id']);
    $transportasi = $rute->transportasi;

    // Get all available seats for the specified transportasi
    $availableSeats = [];
    for ($i = 1; $i <= $transportasi->jumlah; $i++) {
        $seatCode = $transportasi->kode . $i;
        $cekData = json_encode(['kursi' => $seatCode, 'rute' => $dataArray['id'], 'waktu' => $dataArray['waktu']]);
        if ($transportasi->kursi($cekData) === null) {
            $availableSeats[] = $seatCode;
        }
    }

    if (count($availableSeats) < $requestedSeatCount) {
        return redirect()->route('store')->with('error', 'Not enough seats available.');
    }

    // Select the required number of seats
    $selectedSeats = array_slice($availableSeats, 0, $requestedSeatCount);
    $huruf = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    $kodePemesanan = strtoupper(substr(str_shuffle($huruf), 0, 7));

    $temp_kursi = implode(', ', $selectedSeats);
    $harga = $rute->harga * $requestedSeatCount;

    Pemesanan::create([
        'kode' => $kodePemesanan,
        'kursi' => $temp_kursi,
        'waktu' => Carbon::parse($dataArray['event_date'])->format('Y-m-d') . ' ' . $rute->jam,
        'total' => $harga,
        'rute_id' => $rute->id,
        'penumpang_id' => Auth::user()->id
    ]);

    foreach ($selectedSeats as $seat) {
        Pemesanan_Detail::create([
            'pemesananCode' => $kodePemesanan,
            'seatNumber' => $seat,
            'category_id' => $rute->transportasi->category->id
        ]);
    }

    $destination = Auth::user()->username; 
    $message = '[NOTIFIKASI VOS] Pesanan tiket konser VOS Pre Competition Concert, 06 Juli 2024 dengan kode booking: ' . $kodePemesanan . ' telah diterima. 
    Mohon segera mengirimkan bukti transfer ke CS VOS (http://wa.me/6285156651097) 
    Pesanan anda dapat dilacak melalui http://dev-ticketing.voiceofsoulchoirindonesia.com/transaksi/'.$kodePemesanan.' dengan login: 
    Username : '.Auth::user()->username.' 
    Password : password12345678';

    $message_blank = '[NOTIFIKASI VOS]';
    $this->sendWhatsAppMessage_2($destination, $message);
    $this->sendWhatsAppMessage_pesanSuccess($destination, $message_blank, $kodePemesanan);

    return redirect('/transaksi/'.$kodePemesanan)->with('success', 'Pemesanan Tiket ' . $rute->transportasi->category->name . ' Success!');
}

public function pesan_28329873($kursi, $encodedData)
{
    // Decode the JSON string from the URL parameter
    $dataArray = json_decode(urldecode($encodedData), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        // Handle JSON decode error
        return redirect()->route('store')->with('error', 'Invalid data provided.');
    }

    // Get the rute and transportasi details
    $rute = Rute::with('transportasi.category')->find($dataArray['id']);
    $transportasi = $rute->transportasi;

    // Get all available seats for the specified transportasi
    $availableSeats = [];
    for ($i = 1; $i <= $transportasi->jumlah; $i++) {
        $seatCode = $transportasi->kode . $i;
        $cekData = json_encode(['kursi' => $seatCode, 'rute' => $dataArray['id'], 'waktu' => $dataArray['waktu']]);
        if ($transportasi->kursi($cekData) === null) {
            $availableSeats[] = $seatCode;
        }
    }

    //if (count($availableSeats) < $kursi) {
    //    return redirect()->route('store')->with('error', 'Not enough seats available.');
    //}

    // Select the required number of seats
    $selectedSeats = array_slice($availableSeats, 0, $kursi);
    $huruf = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    $kodePemesanan = strtoupper(substr(str_shuffle($huruf), 0, 7));

    $temp_kursi = implode(', ', $selectedSeats);
    $harga = $rute->harga * $kursi;

    Pemesanan::create([
        'kode' => $kodePemesanan,
        'kursi' => $temp_kursi,
        'waktu' => Carbon::parse($dataArray['event_date'])->format('Y-m-d') . ' ' . $rute->jam,
        'total' => $harga,
        'rute_id' => $rute->id,
        'penumpang_id' => Auth::user()->id
    ]);

    foreach ($selectedSeats as $seat) {
        Pemesanan_Detail::create([
            'pemesananCode' => $kodePemesanan,
            'seatNumber' => $seat,
            'category_id' => $rute->transportasi->category->id
        ]);
    }

    $destination = Auth::user()->username; 
    $message = '[NOTIFIKASI VOS] Pesanan tiket konser VOS Pre Competition Concert, 06 Juli 2024 dengan kode booking: ' . $kodePemesanan . ' telah diterima. 
    Mohon segera mengirimkan bukti transfer ke CS VOS (http://wa.me/6285156651097) 
    Pesanan anda dapat dilacak melalui http://dev-ticketing.voiceofsoulchoirindonesia.com/transaksi/'.$kodePemesanan.' dengan login: 
    Username : '.Auth::user()->username.' 
    Password : password12345678';

    $message_blank = '[NOTIFIKASI VOS]';
    $this->sendWhatsAppMessage_2($destination, $message);
    $this->sendWhatsAppMessage_pesanSuccess($destination, $message_blank, $kodePemesanan);

    return redirect('/transaksi/'.$kodePemesanan)->with('success', 'Pemesanan Tiket ' . $rute->transportasi->category->name . ' Success!');
}

public function pesan__canceled20052024($kursi, $encodedData)
{
    // Decode the JSON string from the URL parameter
    $selectedSeats = json_decode(urldecode($kursi), true);
    $dataArray = json_decode(urldecode($encodedData), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        // Handle JSON decode error
        return redirect()->route('store')->with('error', 'Invalid data provided.');
    }

    $requestedSeatCount = count($selectedSeats);

    // Get the rute and transportasi details
    $rute = Rute::with('transportasi.category')->find($dataArray['id']);
    $transportasi = $rute->transportasi;

    // Get all available seats for the specified transportasi
    $availableSeats = [];
    for ($i = 1; $i <= $transportasi->jumlah; $i++) {
        $seatCode = $transportasi->kode . $i;
        $cekData = json_encode(['kursi' => $seatCode, 'rute' => $dataArray['id'], 'waktu' => $dataArray['waktu']]);
        if ($transportasi->kursi($cekData) === null) {
            $availableSeats[] = $seatCode;
        }
    }

    if (count($availableSeats) < $requestedSeatCount) {
        return redirect()->route('store')->with('error', 'Not enough seats available.');
    }

    // Select the required number of seats
    $selectedSeats = array_slice($availableSeats, 0, $requestedSeatCount);
    $huruf = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    $kodePemesanan = strtoupper(substr(str_shuffle($huruf), 0, 7));

    $temp_kursi = implode(', ', $selectedSeats);
    $harga = $rute->harga * $requestedSeatCount;

    Pemesanan::create([
        'kode' => $kodePemesanan,
        'kursi' => $temp_kursi,
        'waktu' => Carbon::parse($dataArray['event_date'])->format('Y-m-d') . ' ' . $rute->jam,
        'total' => $harga,
        'rute_id' => $rute->id,
        'penumpang_id' => Auth::user()->id
    ]);

    foreach ($selectedSeats as $seat) {
        Pemesanan_Detail::create([
            'pemesananCode' => $kodePemesanan,
            'seatNumber' => $seat,
            'category_id' => $rute->transportasi->category->id
        ]);
    }

    $destination = Auth::user()->username; 
    $message = '[NOTIFIKASI VOS] Pesanan tiket konser VOS Pre Competition Concert, 06 Juli 2024 dengan kode booking: ' . $kodePemesanan . ' telah diterima. 
    Mohon segera mengirimkan bukti transfer ke CS VOS (http://wa.me/6285156651097) 
    Pesanan anda dapat dilacak melalui http://dev-ticketing.voiceofsoulchoirindonesia.com/transaksi/'.$kodePemesanan.' dengan login: 
    Username : '.Auth::user()->username.' 
    Password : password12345678';

    $message_blank = '[NOTIFIKASI VOS]';
    $this->sendWhatsAppMessage_2($destination, $message);
    $this->sendWhatsAppMessage_pesanSuccess($destination, $message_blank, $kodePemesanan);

    return redirect('/transaksi/'.$kodePemesanan)->with('success', 'Pemesanan Tiket ' . $rute->transportasi->category->name . ' Success!');
}
  
public function pesan__($data)
    {
        $d = Crypt::decrypt($data);
        $huruf = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $kodePemesanan = strtoupper(substr(str_shuffle($huruf), 0, 7));
    
        $rute = Rute::with('transportasi.category')->find($d['id']);
    
        // Extract selected ticket count from the decrypted data
        $selectedTicketCount = $d['selected_ticket_count'];
    
        // Calculate total price based on selected ticket count
        $totalPrice = $rute->harga * $selectedTicketCount;
    
        $waktu = Carbon::parse($d['waktu'])->format('Y-m-d') . ' ' . $rute->jam;
    
        Pemesanan::create([
            'kode' => $kodePemesanan,
            'kursi' => null, // Since seat selection is bypassed
            'waktu' => $waktu,
            'total' => $totalPrice, // Use calculated total price
            'rute_id' => $rute->id,
            'penumpang_id' => Auth::user()->id
        ]);
    
        return redirect('/transaksi/'.$kodePemesanan)->with('success', 'Pemesanan Tiket ' . $rute->transportasi->category->name . ' Success!');
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
