<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemesananPaymentPromotion extends Model
{
    use HasFactory;

    protected $table = 'pemesanan_payment_promotion';

    protected $fillable = [
        'pemesanan_id',
        'promo_code',
        'discount_type',
        'discount_value',
        'discount_nominal',
    ];

    // Relationship: belongs to one booking
    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'pemesanan_id');
    }
}