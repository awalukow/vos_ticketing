<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('promotions', function (Blueprint $table) {
            // Row status for soft delete
            $table->integer('rowstatus')->default(0)->after('rute_id');

            // Created audit
            $table->unsignedBigInteger('created_by')->nullable()->after('rowstatus');
            $table->timestamp('created_date')->nullable()->after('created_by');

            // Modified audit
            $table->unsignedBigInteger('modified_by')->nullable()->after('created_date');
            $table->timestamp('modified_date')->nullable()->after('modified_by');
        });
    }

    public function down()
    {
        Schema::table('promotions', function (Blueprint $table) {
            $table->dropColumn(['rowstatus', 'created_by', 'created_date', 'modified_by', 'modified_date']);
        });
    }
};