<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // If it's currently an ENUM, we need to redefine it with 'bogo' included
        DB::statement("ALTER TABLE promotions MODIFY COLUMN discount_type ENUM('percent','fixed','bogo') NOT NULL");
    }

    public function down()
    {
        // Revert to original ENUM (optional — be careful not to lose data)
        DB::statement("ALTER TABLE promotions MODIFY COLUMN discount_type ENUM('percent','fixed') NOT NULL");
    }
};