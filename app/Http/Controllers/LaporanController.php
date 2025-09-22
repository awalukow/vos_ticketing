<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Checkin_log;
use App\Models\User;
use App\Models\Pemesanan_Detail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Mail;
use Exception;
use TCPDF;
use PDF;
use App\Mail\EmailNotification; // Assuming you have a Mailable class defined for the email notification
use App\Mail\PaymentConfirmation;
use App\Models\AppSetting; 
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Services\WhatsAppService;


class LaporanController extends Controller
{
    public function __construct(WhatsAppService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }
    public function index()
    {
        $pemesanan = Pemesanan::with('rute', 'penumpang', 'petugas')
                                ->where('rowstatus','>=',0)
                                ->where(function ($query) {
                                    $query->where('isChurch','!=', '1')
                                        ->orWhere('isChurch', null);
                                })
                                ->Where('isFisik','==','0')
                                ->orderBy('created_at', 'desc')->get();
        return view('server.laporan.index', compact('pemesanan'));
    }

    public function transaksi_pending()
    {
        $pemesanan = Pemesanan::with('rute', 'penumpang')
        ->where('status','=','Belum Bayar')
        ->where('expired_date','>', now())
        ->where('rowstatus','>=',0)
        ->where(function ($query) {
            $query->where('isChurch','!=', '1')
                ->orWhere('isChurch', null);
        })
        ->orderBy('created_at', 'desc')->get();
        return view('server.laporan.index', compact('pemesanan'));
    }

    public function ticket_gereja()
    {
        $pemesanan = Pemesanan::with('rute', 'penumpang')
        ->where('isChurch','=', '1')
        //->where(function ($query) {
        //    $query->where('expired_date', '>',now())
        //        ->orWhere('expired_date', null);
        //})
        ->where('rowstatus','>=',0)
        //->where('expired_date','>', now())
        ->orderBy('created_at', 'desc')->get();
        return view('server.laporan.index', compact('pemesanan'));
    }

    public function ticket_fisik()
    {
        $pemesanan = Pemesanan::with('rute', 'penumpang')
        ->where('isFisik','=', '1')
        ->where(function ($query) {
            $query->where('expired_date', '>',now())
                ->orWhere('expired_date', null);
        })
        ->where('rowstatus','>=',0)
        //->where('expired_date','>', now())
        ->orderBy('kode', 'asc')->get();
        return view('server.laporan.index', compact('pemesanan'));
    }

    public function petugas()
    {
        return view('client.petugas');
    }

    /*public function kode(Request $request)
    {
        return redirect()->route('transaksi.show', $request->kode);
    }*/
    
    public function kode(Request $request)
    {
        $kode = $request->kode;
        $source = $request->input('source', 'manual'); // default to manual

        // Only process special logic if it's from QR scan
        if ($source === 'scan') {
            // Validate format: XXXXXX_XX or XXXXXX_XXX
            if (!preg_match('/^([A-Z0-9]{7})_([A-Z]\d{1,2})$/', $kode, $matches)) {
                return back()->with('error', 'Format QR tidak valid. Harus: XXXXXX_XX atau XXXXXX_XXX');
            }

            $pemesananCode = $matches[1]; // e.g., W32B0FV ← THIS is the real kode
            $seatNumber = $matches[2];    // e.g., O6 or O11

            // Find the seat record
            $detail = Pemesanan_Detail::where('pemesananCode', $pemesananCode)
                                    ->where('seatNumber', $seatNumber)
                                    ->first();

            if (!$detail) {
                return back()->with('error', "Kursi {$seatNumber} tidak ditemukan.");
            }

            if ($detail->isCheckedIn) {
                return back()->with('error', "Kursi {$seatNumber} telah check in.");
            }

            // ✅ Mark as checked in
            $detail->update(['isCheckedIn' => true]);

            // ✅ Redirect using pemesananCode, NOT full QR string
            return redirect()->route('transaksi.show', $pemesananCode);
        }

        // For manual input, redirect as-is (assumes user entered pemesanan kode)
        return redirect()->route('transaksi.show', $kode);
    }

    public function uploadBuktiPembayaran(Request $request, $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|file|mimes:jpeg,png,jpg|max:2048',
        ]);

        $transaksi = Pemesanan::find($id);
        if (!$transaksi) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan');
        }

        // Store the uploaded file in the public disk
        $file = $request->file('bukti_pembayaran');
        $filePath = $file->store('bukti_pembayaran', 'public');

        // Save the file path to the database
        $transaksi->bukti_pembayaran = $filePath;
        if($transaksi->isChurch){
            $transaksi->status_pembayaran = 'Sudah Verifikasi';
            $transaksi->status = 'Sudah Bayar'; 
            $transaksi->petugas_id = Auth::user()->id;
        }
        else{
            $transaksi->status_pembayaran = 'Menunggu Verifikasi';
        }
        $transaksi->save();

        // Send email admin
        $messageAdmin = '[NOTIFIKASI VOS] Tabea.! Pembayaran dengan kode pesanan ' . $transaksi->kode . ' sudah diterima. Mohon segera dikonfirmasi!
    Nomor Kontak Pembeli : https://wa.me/' . Auth::user()->username . ', ' . url('/transaksi/' . $transaksi->kode);
        $emailDataAdmin = [
            'subject' => '[VOS] Pesanan Masuk - Kode Booking : ' . $transaksi->kode,
            'content' => $messageAdmin // You can customize the email content as per your requirements
        ];
        if (env('APP_ENV') == 'production') {
            // Get all users who are not Penumpang level
            $admins = User::where('level', '!=', 'Penumpang')
                        ->where('level', '!=', 'Petugas')
                        ->get();
            
            // Send email to each non-Penumpang user
            foreach ($admins as $user) {
                Mail::to($user->email)->send(new EmailNotification($emailDataAdmin));
                if($user->contactPerson){
                    $responseAdmin = $this->whatsAppService->sendWA($user->contactPerson, 'HX7f3af4dfbf1e8eb58d382246dd8cb87e', [
                        "code" => "" . $transaksi->kode . "",
                    ]);
                }
            }
        }
        else{
            //Mail::to("axcellentwalukow@gmail.com")->send(new EmailNotification($emailDataAdmin));
            //Mail::to("cs@voiceofsoulchoir.id")->send(new EmailNotification($emailData)); // cs
            //Mail::to("ticketing@voiceofsoulchoir.id")->send(new BookingConfirmation($emailData)); // cs
            //$responseAdmin = $this->whatsAppService->sendMessage('+6285156651097', $messageAdmin);
            $responseAdmin = $this->whatsAppService->sendWA('6285156651097', 'HX7f3af4dfbf1e8eb58d382246dd8cb87e', [
                    "code" => "" . $transaksi->kode . "",
            ]);
        }

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi.');
    }

    public function pembayaran($id)
{
    $pemesanan = Pemesanan::find($id);
    $customerService = AppSetting::getCustomerService();
    
    // Check if the booking exists
    if (!$pemesanan) {
        return redirect()->back()->with('error', 'Pemesanan tidak ditemukan!');
    }

    // Get passenger info
    $penumpang = DB::table('users')
                ->join('pemesanan', 'users.id', '=', 'pemesanan.penumpang_id')
                ->select('users.username', 'users.email', 'users.contactPerson')
                ->where('pemesanan.kode', '=', $pemesanan->kode)
                ->first();

    if (!$penumpang) {
        return redirect()->back()->with('error', 'Data penumpang tidak ditemukan!');
    }

    // Verify the payment
    $pemesanan->status = 'Sudah Bayar';
    $pemesanan->petugas_id = Auth::user()->id;
    $pemesanan->status_pembayaran = 'Sudah Verifikasi';
    $pemesanan->save();
    // Process seats - handle both string and array formats
    $seats = $pemesanan->kursi;
    $seatArray = [];
    
    // Check if kursi is a JSON string
    if (is_string($seats) && substr($seats, 0, 1) === '[') {
        $seatArray = json_decode($seats, true);
        if (!is_array($seatArray)) {
            $seatArray = [$seats];
        }
    } else {
        // If it's a single seat or number
        $seatArray = [$seats];
    }
    
    // Clean up seat names by removing brackets and quotes
    $cleanedSeats = array_map(function($seat) {
        return trim($seat, '[]"');
    }, $seatArray);

    // Prepare email data for payment confirmation
    $emailData = [
        'subject' => '[VOS] Pesanan anda sudah dikonfirmasi! - Kode Booking : ' . $pemesanan->kode,
        'eventName' => 'VOS 20th Anniversary Concert @ Balai Resital Kartanegara',
        'bookingCode' => $pemesanan->kode,
        'eventDate' => '09 November 2025',
        'eventTime' => '18:30',
        'seats' => implode(', ', $cleanedSeats),
        'seatCount' => count($cleanedSeats),
        'totalAmount' => 'Rp ' . number_format($pemesanan->total, 0, ',', '.'),
        'transactionUrl' => url('/transaksi/' . $pemesanan->kode),
        'helpCenterUrl' => url('/help'),
        'termsUrl' => url('/terms'),
        'privacyUrl' => url('/privacy'),
        'cs' => $customerService->value ?? '',
    ];

    try {
        // Send payment confirmation email
        Mail::to($penumpang->email)->send(new PaymentConfirmation($emailData));
        if (env('APP_ENV') == 'production') {
            $WAtoCustomer = $this->whatsAppService->sendWA($penumpang->username, 'HX8059954450eebff37d0c37774d561809', [
                    "code" => "" . $pemesanan->kode . "",
                ]);
             if(auth()->user()->level != 'Penumpang'){
                $WAtoCustomer = $this->whatsAppService->sendWA($penumpang->contactPerson, 'HX8059954450eebff37d0c37774d561809', [
                    "code" => "" . $pemesanan->kode . "",
                ]);
            }
            else{
                $WAtoCustomer = $this->whatsAppService->sendWA($penumpang->username, 'HX8059954450eebff37d0c37774d561809', [
                    "code" => "" . $pemesanan->kode . "",
                ]);
            }
        }
        else{
            if(auth()->user()->level != 'Penumpang'){
                $WAtoCustomer = $this->whatsAppService->sendWA('6285156651097', 'HX8059954450eebff37d0c37774d561809', [
                    "code" => "" . $pemesanan->kode . "",
                ]);
            }
            else{
                $WAtoCustomer = $this->whatsAppService->sendWA('6285156651097', 'HX8059954450eebff37d0c37774d561809', [
                    "code" => "" . $pemesanan->kode . "",
                ]);
            }
        }
        
        // Send copy to CS
        Mail::to("ticketing@voiceofsoulchoir.id")->send(new PaymentConfirmation($emailData));

    } catch (\Exception $e) {
        // Log the error but don't fail the payment verification
        \Log::error('Error sending payment confirmation: ' . $e->getMessage());
        \Log::error('Error trace: ' . $e->getTraceAsString());
    }

    return redirect()->back()->with('success', 'Pembayaran Ticket Success!');
}

    public function history()
    {
        //$pemesanan = Pemesanan::with('rute.transportasi')->where('penumpang_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        $pemesanan = Pemesanan::with(['rute.transportasi.category'])
        ->where('penumpang_id', Auth::user()->id)
        ->where('rowstatus', '>=', '0')
        ->orderBy('created_at', 'desc')
        ->get();

        return view('client.history', compact('pemesanan'));
    }
    
    public function sendSMS_NUSSASMS($destination, $message)
    {
        $BASE_URL = 'https://api.wachat-api.com/wachat_api/1.0/message';
        $apiKey = '3D74D32273BA78AA8C3E9A996E208FB2'; // Replace with your actual API key
        $deviceId = '11055-171379261223689'; // Replace with your device ID

        $response = Http::withHeaders([
            'APIKey' => $apiKey,
            'Content-Type' => 'application/json',
        ])->post($BASE_URL, [
            'destination' => $destination,
            'message' => $message,
            'queue' => $deviceId,
        ]);

        if ($response->successful()) {
            // Message sent successfully
            // You can handle the response here
            $responseData = $response->json(); // Convert response to array or object
            // Handle the response as needed
            return $responseData;
        } else {
            // Error occurred
            $errorMessage = $response->body(); // Get the error message
            // Handle the error as needed
            // For example:
            // return response()->json(['error' => $errorMessage], 500);
            return null; // Or handle the error in another way
        }
    }

    public function sendWhatsAppMessage($destination, $message)
    {
        $url = 'https://wa.srv3.waboxs.com/send-message';
        $apiKey = '5307c9fcda1ebd5e834ecde69ea16da70ee4d104';
        $deviceId = '7547';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 0);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_POST, 1);

        $postData = [
            'id_device' => $deviceId,
            'api-key' => $apiKey,
            'no_hp' => $destination,
            'pesan' => $message
        ];
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
    public function sendWhatsAppMessage_2($destination, $message)
    {
        $url = 'https://wa.srv34.wapanels.com/send-message';
        $apiKey = '5307c9fcda1ebd5e834ecde69ea16da70ee4d104'; // Insert your API key here
    
        $data = [
            'api_key' => $apiKey,
            'sender' => '6285781788462',
            'number' => $destination,
            'message' => $message
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

    public function updateCheckIn(Request $request, $id)
    {
        $request->validate([
            'seatNumber' => 'required|integer'
        ]);

        $transaksi = Pemesanan::find($id);

        if (!$transaksi) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan');
        } elseif ($request->input('seatNumber') > ($transaksi->kursi - $transaksi->seatCheckin)) {
            return redirect()->back()->with('error', 'Jumlah check-in melebihi sisa kursi belum check-in');
        } elseif ($transaksi->seatCheckin == $transaksi->kursi) {
            return redirect()->back()->with('error', 'Semua seat sudah check-in');
        } elseif ($request->input('seatNumber') <= 0){
            return redirect()->back()->with('error', 'Jumlah seat checkin harus > 0');
        }

        $transaksi->seatCheckin += $request->input('seatNumber');
        $transaksi->save();

        Checkin_log::create([
            'pemesananId' => $transaksi->id,
            'petugasId' => Auth::user()->id,
            'kursi' => $request->input('seatNumber')
        ]);
        //$checkin_log = new Checkin_log();
        //$checkin_log->pemesananId = $transaksi->kode;
        //$checkin_log->kursi = $transaksi->seatCheckin;
        //$checkin_log->save();

        return redirect()->back()->with('success', 'Checkin Berhasil.');
    }

    public function cancelOrder(Request $request, $id)
    {
        $transaksi = Pemesanan::find($id);
        
        if(Auth::user()->level == "SuperAdmin"){
            
        }
        else {
            if (!$transaksi) {
                return redirect()->back()->with('error', 'Transaksi tidak ditemukan');
            } elseif ($transaksi->status == 'Sudah Bayar') {
                return redirect()->back()->with('error', 'Pesanan Sudah Dibayar');
            } elseif ($transaksi->status_pembayaran) {
                return redirect()->back()->with('error', 'Pesanan masih dalam status RESERVED');
            }
        }
        
        $transaksi->expired_date = '1970-01-01 23:59:59';
        $transaksi->referral = $transaksi->referral . ' | CANCELED by ' . Auth::user()->name;
        //$transaksi->status_pembayaran = 'Cancel by Admin';
        $transaksi->save();

        return redirect()->back()->with('success', 'Berhasil Cancel.');
    }

    public function resendTicketEmail($id)
    {
        $pemesanan = Pemesanan::find($id);
        
        if (!$pemesanan) {
            return response()->json(['message' => 'Pemesanan tidak ditemukan'], 404);
        }
        
        try {
            // Process seats
            $seats = $pemesanan->kursi;
            $seatArray = [];
            
            if (is_string($seats) && substr($seats, 0, 1) === '[') {
                $seatArray = json_decode($seats, true);
                if (!is_array($seatArray)) {
                    $seatArray = [$seats];
                }
            } else {
                $seatArray = [$seats];
            }
            
            $cleanedSeats = array_map(function($seat) {
                return trim($seat, '[]"');
            }, $seatArray);

            // Prepare email data
            $emailData = [
                'subject' => '[VOS] Pesanan anda sudah dikonfirmasi! - Kode Booking : ' . $pemesanan->kode,
                'bookingCode' => $pemesanan->kode,
                'eventName' => 'VOS 20th Anniversary Concert @ Balai Resital Kartanegara',
                'eventDate' => '09 November 2025',
                'eventTime' => '18:30',
                'seats' => implode(', ', $cleanedSeats),
                'totalAmount' => 'Rp ' . number_format($pemesanan->total, 0, ',', '.'),
                'transactionUrl' => url('/transaksi/' . $pemesanan->kode),
                'helpCenterUrl' => url('/help'),
                'termsUrl' => url('/terms'),
                'privacyUrl' => url('/privacy'),
                'cs' => AppSetting::getCustomerService()->Value ?? '',  
            ];

            // Send email
            Mail::to($pemesanan->penumpang->email)->send(new PaymentConfirmation($emailData));
            
            return response()->json(['message' => 'Tiket berhasil dikirim ulang ke email']);
            
        } catch (\Exception $e) {
            \Log::error('Resend ticket email failed: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal mengirim ulang tiket'], 500);
        }
    }
}
