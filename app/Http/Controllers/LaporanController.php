<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Mail;
use Exception;
use TCPDF;
use App\Mail\EmailNotification; // Assuming you have a Mailable class defined for the email notification


class LaporanController extends Controller
{
    public function index()
    {
        $pemesanan = Pemesanan::with('rute', 'penumpang', 'petugas')
                                ->where('rowstatus','>=',0)
                                ->where('isChurch','!=', '1')
                                ->orderBy('created_at', 'desc')->get();
        return view('server.laporan.index', compact('pemesanan'));
    }

    public function transaksi_pending()
    {
        $pemesanan = Pemesanan::with('rute', 'penumpang')
        ->where('status','=','Belum Bayar')
        ->where('expired_date','>', now())
        ->where('rowstatus','>=',0)
        ->where('isChurch','!=', '1')
        ->orderBy('created_at', 'desc')->get();
        return view('server.laporan.index', compact('pemesanan'));
    }

    public function ticket_gereja()
    {
        $pemesanan = Pemesanan::with('rute', 'penumpang')
        ->where('isChurch','=', '1')
        ->where(function ($query) {
            $query->where('expired_date', '>',now())
                ->orWhere('expired_date', null);
        })
        ->where('rowstatus','>=',0)
        //->where('expired_date','>', now())
        ->orderBy('created_at', 'desc')->get();
        return view('server.laporan.index', compact('pemesanan'));
    }

    public function petugas()
    {
        return view('client.petugas');
    }

    public function kode(Request $request)
    {
        return redirect()->route('transaksi.show', $request->kode);
    }

    public function show($id)
    {
        $data = Pemesanan::with('rute.transportasi.category', 'penumpang')->where('kode', $id)->where('rowstatus','>=',0)->first();
        if ($data) {
            return view('server.laporan.show', compact('data'));
        } else {
            return redirect()->back()->with('error', 'Kode Transaksi Tidak Ditemukan!');
        }
    }

    public function pembayaran_old($id)
    {
        Pemesanan::find($id)->update([
            'status' => 'Sudah Bayar',
            'petugas_id' => Auth::user()->id
        ]);

        return redirect()->back()->with('success', 'Pembayaran Ticket Success!');
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
        $transaksi->status_pembayaran = 'Menunggu Verifikasi';
        $transaksi->save();

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi.');
    }



    public function pembayaran($id)
    {
        $pemesanan = Pemesanan::find($id);
        $penumpang = DB::table('users')
                    ->join('pemesanan', 'users.id', '=', 'pemesanan.penumpang_id')
                    ->select('users.username', 'users.email')
                    ->where('pemesanan.kode', '=', $pemesanan->kode)
                    ->first(); // Retrieve the first result
        // Check if the booking exists
        if (!$pemesanan) {
            return redirect()->back()->with('error', 'Pemesanan tidak ditemukan!');
        }

        // Verify the payment
        $pemesanan->status = 'Sudah Bayar';
        $pemesanan->petugas_id = Auth::user()->id;
        $pemesanan->status_pembayaran = 'Sudah Verifikasi';
        $pemesanan->save();

        // Define $destination and $message for WA
        $destination = $penumpang->username; // Replace with the destination number
        $message = '*[NOTIFIKASI VOS] PEMBAYARAN BERHASIL*
Tiket konser INTERVAL | VOS Pre Competition Concert, 20 Juli 2024.

Kode booking: ' . $pemesanan->kode . ' 
Jumlah Tiket: ' . $pemesanan->kursi . '
Total Biaya: ' . $pemesanan->total . '
Status Pembayaran: *BERHASIL*

untuk informasi lebih lanjut hubungi: http://wa.me/6285823536364 (Jean) atau http://wa.me/6287780553668 (Tiara)'; 

        $messageEmail = 'Pembayaran sudah diterima dan diverifikasi. Tiket konser VOS Pre Competition Concert, 06 Juli 2024 dengan kode booking: ' . $pemesanan->kode . ' sudah sudah terkonfirmasi. 
        berikut adalah ringkasan e-tiket anda: 
        Kode Booking : '. $pemesanan->kode . '
        Nama Event : Interval | Pre-Competition Concert 
        Jumlah Kursi : '. $pemesanan->kursi . '
        '; 

        // Call sendSMS method
        //$response = $this->sendWhatsAppMessage_2($destination, $message);

        /*    
        if ($response) {
            echo "WA SUCCESS";
        } else {
            echo "WA FAILED";
        }
        */

        // Send email
        $emailData = [
            'subject' => '[VOS] Pesanan anda sudah dikonfirmasi! - Kode Booking : ' . $pemesanan->kode ,
            'content' => $messageEmail
        ];
        Mail::to($penumpang->email)->send(new EmailNotification($emailData));


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
}
