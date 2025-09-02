<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan_Detail extends Model
{
    use HasFactory;

    protected $fillable = [
        'pemesananCode',
        'seatNumber',
        'isCheckedIn'
    ];
    protected $table = 'pemesanan_detail';
    /**
     * A Pemesanan_Detail belongs to a Pemesanan (booking)
     */
    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'pemesananCode', 'kode');
    }
}
