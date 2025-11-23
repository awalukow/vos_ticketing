<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Promotion::create([
            'code' => 'WELCOME10',
            'discount_type' => 'percent',
            'discount_value' => 10.00,
            'max_uses' => 100,
            'expires_at' => now()->addMonth(),
            'is_active' => true,
        ]);

        \App\Models\Promotion::create([
            'code' => 'DISKON50K',
            'discount_type' => 'fixed',
            'discount_value' => 50000.00,
            'max_uses' => 10,
            'expires_at' => now()->addDays(7),
            'is_active' => true,
        ]);
    }
}
