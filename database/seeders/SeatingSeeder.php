<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SeatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $map = SeatingMap::create([
            'name' => 'Theater Layout 1',
            'event_id' => null,
            'total_rows' => 5,
            'total_columns' => 8
        ]);

        foreach (range(1, 5) as $row) {
            foreach (range(1, 8) as $col) {
                Seat::create([
                    'seating_map_id' => $map->id,
                    'label' => chr(64 + $row) . $col, // A1, A2...
                    'row' => $row,
                    'column' => $col,
                    'status' => 'available'
                ]);
            }
        }
    }
}
