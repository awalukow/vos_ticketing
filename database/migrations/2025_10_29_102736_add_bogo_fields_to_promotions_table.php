<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('promotions', function (Blueprint $table) {
            // BOGO logic: Buy X, Get Y Free
            $table->integer('buy_quantity')->nullable()->after('min_order');
            $table->integer('get_free')->nullable()->after('buy_quantity');

            // Allow discount_type to be 'bogo'
        });
    }

    public function down()
    {
        Schema::table('promotions', function (Blueprint $table) {
            $table->dropColumn(['buy_quantity', 'get_free']);
        });
    }
};