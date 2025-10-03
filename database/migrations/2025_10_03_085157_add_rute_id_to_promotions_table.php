<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('promotions', function (Blueprint $table) {
            // Add rute_id with nullable (so general promos still work)
            $table->unsignedBigInteger('rute_id')->nullable()->after('penumpang_id');

            // Optional: Add foreign key constraint
            $table->foreign('rute_id')->references('id')->on('rute')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('promotions', function (Blueprint $table) {
            $table->dropForeign(['rute_id']);
            $table->dropColumn('rute_id');
        });
    }
};