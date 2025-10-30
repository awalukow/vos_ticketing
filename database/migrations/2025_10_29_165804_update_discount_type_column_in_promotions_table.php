<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDiscountTypeColumnInPromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE promotions MODIFY discount_type ENUM('percent','fixed','bogo','ticket_discount') NOT NULL");
    }

    public function down()
    {
        DB::statement("ALTER TABLE promotions MODIFY discount_type ENUM('percent','fixed','bogo') NOT NULL");
    }
}
