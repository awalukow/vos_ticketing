<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g., WELCOME10
            $table->enum('discount_type', ['percent', 'fixed']); // percent or fixed amount
            $table->decimal('discount_value', 10, 2); // e.g., 10% or Rp50000
            $table->integer('max_uses')->default(1); // max number of times it can be used
            $table->integer('used_count')->default(0); // how many times it has been used
            $table->dateTime('expires_at')->nullable(); // expiry date/time
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Optional: Limit per user
            // $table->integer('per_user_limit')->default(1);
        });
    }

    public function down()
    {
        Schema::dropIfExists('promotions');
    }
};