<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Rute;
use App\Models\Transportasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
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
        // Temp list table
        $rute_table = Rute::with('transportasi.category')->get();
        $church_reference_table = Pemesanan::where('isChurch', '1')
                                            ->distinct()
                                            ->pluck('referral')
                                            ->toArray();
        $ruteCount = Rute::count();
        $pendapatan = Pemesanan::where('status', 'Sudah Bayar')->where('rowstatus', '>=', 0)->sum('total');
        $transportasiCount = Transportasi::count();
        $userCount = User::count();
        $pendingTicketCount = Pemesanan::where('status', 'Belum Bayar')
                                       ->where('rowstatus', '>=', 0)
                                       ->where('isChurch', '!=', '1')
                                       ->where('expired_date', '>', now())
                                       ->count();
        $paidTicketCount = Pemesanan::where('status', 'Sudah Bayar')
                                    ->where('rowstatus', '>=', 0)
                                    ->sum('kursi');
    
        // Add calculations for each route
        foreach ($rute_table as $rute) {
            // Total tickets sold and revenue for this route
            $rute->tickets_sold = Pemesanan::where('rute_id', $rute->id)
                                            ->where('status', 'Sudah Bayar')
                                            ->where('rowstatus', '>=', 0)
                                            ->sum('kursi');
            $rute->nominal_terjual = Pemesanan::where('rute_id', $rute->id)
                                               ->where('status', 'Sudah Bayar')
                                               ->where('rowstatus', '>=', 0)
                                               ->sum('total');
    
            // Calculate remaining seats for this route
            $total_seats = $rute->transportasi->jumlah;
            $sold_seats = Pemesanan::where('rute_id', $rute->id)
                                   ->where(function ($query) {
                                       $query->where('status', 'Sudah Bayar')
                                             ->orWhere('status_pembayaran', 'Menunggu Verifikasi');
                                   })
                                   ->where('rowstatus', '>=', 0)
                                   ->sum('kursi');
            $rute->unpaid_seat = Pemesanan::where('rute_id', $rute->id)
                                           ->where('status', 'Belum Bayar')
                                           ->where('rowstatus', '>=', 0)
                                           ->where('isChurch', '0')
                                           ->where(function ($query) {
                                                $query->where('expired_date', '>', now())
                                                    ->orWhereNull('expired_date');
                                            })
                                           ->sum('kursi');
            $rute->unpaid_seat_church = Pemesanan::where('rute_id', $rute->id)
                                                  ->where('status', 'Belum Bayar')
                                                  ->where('rowstatus', '>=', 0)
                                                  ->where('isChurch', '1')
                                                  ->where(function ($query) {
                                                      $query->where('expired_date', '>', now())
                                                            ->orWhereNull('expired_date');
                                                  })
                                                  ->sum('kursi');
            
            $rute->sisa_kursi = $total_seats - $sold_seats - $rute->unpaid_seat - $rute->unpaid_seat_church;
        }
    
        // Collect church data
        $churches = [];
    
        foreach ($church_reference_table as $referral) {
            $church = new \stdClass();
            $church->sold_qty = Pemesanan::where('referral', $referral)
                                         ->where('status', 'Sudah Bayar')
                                         ->where('isChurch', '1')
                                         ->count();
            $church->sold_nominal = Pemesanan::where('referral', $referral)
                                             ->where('status', 'Sudah Bayar')
                                             ->where('isChurch', '1')
                                             ->sum('total');
            $church->unsold_qty = Pemesanan::where('referral', $referral)
                                           ->where('status', 'Belum Bayar')
                                           ->where('isChurch', '1')
                                           ->where(function ($query) {
                                               $query->where('expired_date', '>', now())
                                                     ->orWhereNull('expired_date');
                                           })
                                           ->count();
            $expired_date = Pemesanan::where('referral', $referral)
                                     ->distinct()
                                     ->where('isChurch', '1')
                                     ->select(DB::raw('DATE(expired_date) as expired_date'))
                                     ->pluck('expired_date')
                                     ->first();
            
            if($referral){
                $church->name = $referral;
            } else {
                $church->name = 'Belum Teralokasi';
            }

            if ($expired_date) {
                $church->expiry_date = Carbon::parse($expired_date)->format('d-M-Y');
            } else {
                $church->expiry_date = null;
            }

            if($expired_date < NOW()){
                $church->isExpired = false;
            }
            else {
                $church->isExpired = true;
            }

            $churches[] = $church;
        }

        // Separate 'Belum Teralokasi' church from the rest
        $belumTerdeteksi = [];
        $otherChurches = [];

        foreach ($churches as $church) {
            if ($church->name == 'Belum Teralokasi') {
                $belumTerdeteksi[] = $church;
            } else {
                $otherChurches[] = $church;
            }
        }

        // Merge arrays, putting 'Belum Teralokasi' at the end
        $sortedChurches = array_merge($otherChurches, $belumTerdeteksi);

        return view('server.home', compact(
            'ruteCount', 'pendapatan', 'rute_table', 'transportasiCount', 'userCount', 
            'pendingTicketCount', 'paidTicketCount', 'sortedChurches'
        ));
    }
}
