<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCheckinFieldsToPemesananDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pemesanan_detail', function (Blueprint $table) {
            $table->timestamp('checkin_timestamp')->nullable()->after('isCheckedIn');
            $table->unsignedBigInteger('petugas_id')->nullable()->after('checkin_timestamp');
            
            // Optional: Add foreign key constraint (if you want referential integrity)
            $table->foreign('petugas_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pemesanan_detail', function (Blueprint $table) {
            //
        });
    }
}
