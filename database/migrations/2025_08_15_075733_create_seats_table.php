<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seating_map_id')->constrained()->onDelete('cascade');
            $table->string('label'); // e.g., A1, B2
            $table->integer('row');
            $table->integer('column');
            $table->enum('status', ['available', 'booked', 'locked', 'blocked'])->default('available');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('seats');
    }
}
