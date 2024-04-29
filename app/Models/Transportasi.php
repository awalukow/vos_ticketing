<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transportasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'kode',
        'jumlah',
        'category_id'
    ];

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function _kursi($cekData)
    {
        // Decode the JSON data
        $data = json_decode($cekData, true);

        // Fetch occupied seat numbers for the given data
        $occupiedSeats = Pemesanan_Detail::where('pemesananCode', 'LIKE', '%' . $data['kursi'] . '%')
                                        //->where('rute_id', $data['rute'])
                                        //->where('waktu', $data['waktu'])
                                        ->pluck('seatNumber')
                                        ->toArray();

        return $occupiedSeats;
    }

    public function kursi($id)
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


    public function __kursi($id)
    {
        $data = json_decode($id, true);
        $kursi = Pemesanan:://where('rute_id', $data['rute'])
                            //->where('waktu', $data['waktu'])
                            where('kursi', $data['kursi'])->count();

        if ($kursi > 0) {
            return null;
        } else {
            return $id;
        }
    }

    protected $table = 'transportasi';
}
