<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;

    protected $table = 'pemesanan';
    protected $fillable = [
        'kode',
        'kursi',
        'waktu',
        'event_date',
        'total',
        'status',
        'rute_id',
        'penumpang_id',
        'petugas_id',
        'bukti_pembayaran',
        'status_pembayaran',
        'referral',
        'expired_date',
        'rowstatus',
        'isChurch',
        'isFisik',
        'seatCheckin'
    ];

    /**
     * A Pemesanan belongs to a Rute
     */
    public function rute()
    {
        return $this->belongsTo(Rute::class, 'rute_id');
    }

    /**
     * A Pemesanan belongs to a Penumpang (User)
     */
    public function penumpang()
    {
        return $this->belongsTo(User::class, 'penumpang_id');
    }

    /**
     * A Pemesanan may belong to a Petugas (User)
     */
    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    /**
     * A Pemesanan has many Pemesanan_Detail (seat details)
     */
    public function pemesananDetails()
    {
        return $this->hasMany(Pemesanan_Detail::class, 'pemesananCode', 'kode');
    }
}