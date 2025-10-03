<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('promotions', function (Blueprint $table) {
            $table->unsignedBigInteger('penumpang_id')->nullable()->after('is_active');
            
            // Optional: Add foreign key constraint if needed
            $table->foreign('penumpang_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('promotions', function (Blueprint $table) {
            $table->dropForeign(['penumpang_id']);
            $table->dropColumn('penumpang_id');
        });
    }
};