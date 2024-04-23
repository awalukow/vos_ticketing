<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;


class LaporanController extends Controller
{
    public function index()
    {
        $pemesanan = Pemesanan::with('rute', 'penumpang')->orderBy('created_at', 'desc')->get();
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
        $data = Pemesanan::with('rute.transportasi.category', 'penumpang')->where('kode', $id)->first();
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

    public function pembayaran($id)
    {
        $pemesanan = Pemesanan::find($id);

        // Check if the booking exists
        if (!$pemesanan) {
            return redirect()->back()->with('error', 'Pemesanan tidak ditemukan!');
        }

        // Verify the payment
        $pemesanan->status = 'Sudah Bayar';
        $pemesanan->petugas_id = Auth::user()->id;
        $pemesanan->save();

        // Define $destination and $message for SMS
        $destination = '6285156651097'; // Replace with the destination number
        $message = '[NOTIFIKASI VOS] Tiket konser VOS Pre Competition Concert, 22 April 2024 dengan kode booking: ' . $pemesanan->kode . ' sudah dikonfirmasi.'; 

        // Call sendSMS method
        $response = $this->sendSMS($destination, $message);

        if ($response) {
            echo "WA SUCCESS";
        } else {
            echo "WA FAILED";
        }

        return redirect()->back()->with('success', 'Pembayaran Ticket Success!');
    }

    public function history()
    {
        //$pemesanan = Pemesanan::with('rute.transportasi')->where('penumpang_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        $pemesanan = Pemesanan::with(['rute.transportasi.category'])
    ->where('penumpang_id', Auth::user()->id)
    ->orderBy('created_at', 'desc')
    ->get();

        return view('client.history', compact('pemesanan'));
    }
    public function sendSMS($destination, $message)
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
}
