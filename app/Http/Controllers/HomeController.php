<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Rute;
use App\Models\Transportasi;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
 
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index2()
    {
        $rute = Rute::count();
        $pendapatan = Pemesanan::where('status', 'Sudah Bayar')->sum('total');
        $rute_table = Rute::get();
        $transportasi = Transportasi::count();
        $user = User::count();
        $pendingTicket = Pemesanan::where('status','=','Belum Bayar')->count();
        return view('server.home', compact('rute', 'pendapatan', 'rute_table', 'transportasi', 'user', 'pendingTicket'));
    }

    public function index()
    {
        $ruteCount = Rute::count();
        $pendapatan = Pemesanan::where('status', 'Sudah Bayar')->sum('total');
        $rute_table = Rute::with('transportasi')->get();
        $transportasiCount = Transportasi::count();
        $userCount = User::count();
        $pendingTicketCount = Pemesanan::where('status', 'Belum Bayar')->count();
        $paidTicketCount = Pemesanan::where('status', 'Sudah Bayar')->sum('kursi');

        // Add calculations for each route
        foreach ($rute_table as $rute) {
            // Total tickets sold and revenue for this route
            $rute->tickets_sold = Pemesanan::where('rute_id', $rute->id)->where('status', 'Sudah Bayar')->sum('kursi');
            $rute->nominal_terjual = Pemesanan::where('rute_id', $rute->id)->where('status', 'Sudah Bayar')->sum('total');

            // Calculate remaining seats for this route
            $total_seats = $rute->transportasi->jumlah;
            $sold_seats = Pemesanan::where('rute_id', $rute->id)->where('status', 'Sudah Bayar')->sum('kursi');
            $rute->unpaid_seat = Pemesanan::where('rute_id', $rute->id)->where('status', 'Belum Bayar')->sum('kursi');
            $rute->sisa_kursi = $total_seats - $sold_seats - $rute->unpaid_seat;
        }

        return view('server.home', compact('ruteCount', 'pendapatan', 'rute_table', 'transportasiCount', 'userCount', 'pendingTicketCount', 'paidTicketCount'));
    }
}
