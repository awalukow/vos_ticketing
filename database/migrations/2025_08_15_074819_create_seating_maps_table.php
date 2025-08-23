<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeatingMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('seating_maps', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., Theater Layout 1
            $table->unsignedBigInteger('event_id')->nullable();
            $table->integer('total_rows');
            $table->integer('total_columns');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('seating_maps');
    }
}
