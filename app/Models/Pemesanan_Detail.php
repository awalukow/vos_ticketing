<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan_Detail extends Model
{
    use HasFactory;

    protected $fillable = [
        'pemesananCode',
        'seatNumber'
    ];
    protected $table = 'Pemesanan_Detail';
}
