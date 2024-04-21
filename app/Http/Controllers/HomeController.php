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
    public function index()
    {
        $rute = Rute::count();
        $pendapatan = Pemesanan::where('status', 'Sudah Bayar')->sum('total');
        $transportasi = Transportasi::count();
        $user = User::count();
        return view('server.home', compact('rute', 'pendapatan', 'transportasi', 'user'));
    }
}
