<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transportasi extends Model
{
    use HasFactory;

    protected $table = 'transportasi';
    protected $fillable = [
        'name',
        'kode',
        'jumlah',
        'category_id',
        'isForAdmin'
    ];

    /**
     * Each Transportasi belongs to a Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Each Transportasi has many Rutes
     */
    public function rutes()
    {
        return $this->hasMany(Rute::class, 'transportasi_id');
    }

    /**
     * Helper method to check seat availability
     * Used in Blade view to determine if a seat is taken and not expired
     *
     * @param string $cekData JSON string containing: 'kursi', 'rute', 'waktu'
     * @return string|null Returns seat number if available, null if taken
     */
    public function kursi($cekData)
    {
        $data = json_decode($cekData, true);

        $count = Pemesanan_Detail::where('seatNumber', $data['kursi'])
            ->whereHas('pemesanan', function ($query) use ($data) {
                $query->where('rute_id', $data['rute'])
                    //->where('waktu', 'like', $data['waktu'] . '%')
                    ->where('rowstatus', '>=', 0)
                    ->where(function ($q) {
                        $q->where(function ($sub) {
                            $sub->where('expired_date', '>', now())
                                ->where('status', 'Belum Bayar');
                        })->orWhere(function ($sub) {
                            $sub->whereNotNull('bukti_pembayaran')
                                ->where('status_pembayaran', 'Menunggu Verifikasi');
                        })->orWhere('status', 'Sudah Bayar');
                    });
            })
            ->count();

        return $count > 0 ? null : $data['kursi'];
    }

    public function kursi____($id)
    {
        $data = json_decode($id, true);

        $kursi = Pemesanan_Detail:://where('rute_id', $data['rute'])
                            //->where('waktu', $data['waktu'])
                            where('seatNumber', $data['kursi'])->count();
        if ($kursi > 0) {
            return null;
        } else {
            return $id;
        }
    }
    /**
     * Legacy method - may be removed if unused
     */
    public function _kursi($cekData)
    {
        $data = json_decode($cekData, true);

        return Pemesanan_Detail::where('pemesananCode', 'LIKE', '%' . $data['kursi'] . '%')
            ->pluck('seatNumber')
            ->toArray();
    }

    /**
     * Another legacy method - likely unused
     */
    public function __kursi($id)
    {
        $data = json_decode($id, true);
        $kursi = Pemesanan::where('kursi', $data['kursi'])->count();

        return $kursi > 0 ? null : $id;
    }
}