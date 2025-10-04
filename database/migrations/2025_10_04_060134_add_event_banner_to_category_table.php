<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEventBannerToCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category', function (Blueprint $table) {
            $table->string('event_banner')->nullable()->after('slug'); // or TEXT if storing long URLs
        });
    }

    public function down()
    {
        Schema::table('category', function (Bluebprint $table) {
            $table->dropColumn('event_banner');
        });
    }
}
