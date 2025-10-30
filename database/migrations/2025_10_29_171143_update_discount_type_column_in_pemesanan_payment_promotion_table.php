<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDiscountTypeColumnInPemesananPaymentPromotionTable extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE pemesanan_payment_promotion MODIFY discount_type ENUM('percent','fixed','bogo','ticket_discount') NOT NULL");
    }

    public function down()
    {
        DB::statement("ALTER TABLE pemesanan_payment_promotion MODIFY discount_type ENUM('percent','fixed','bogo') NOT NULL");
    }
}
