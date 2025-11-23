<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkin_log extends Model
{
    use HasFactory;

    protected $fillable = [
        'pemesananId',
        'kursi',
        'petugasId'
    ];
    protected $table = 'checkin_log';
}
