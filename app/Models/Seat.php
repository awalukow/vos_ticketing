<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = ['seating_map_id', 'label', 'row', 'column', 'status'];

    public function seatingMap()
    {
        return $this->belongsTo(SeatingMap::class);
    }
}

