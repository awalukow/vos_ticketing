<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeatingMap extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'event_id', 'total_rows', 'total_columns'];

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }
}

