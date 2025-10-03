<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pemesanan_payment_promotion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pemesanan_id'); // Foreign key to pemesanan.id
            $table->string('promo_code');               // Copy of the code used
            $table->enum('discount_type', ['percent', 'fixed']);
            $table->decimal('discount_value', 10, 2);   // % or fixed amount
            $table->decimal('discount_nominal', 12, 2); // Actual Rp value deducted
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('pemesanan_id')
                  ->references('id')
                  ->on('pemesanan')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pemesanan_payment_promotion');
    }
};