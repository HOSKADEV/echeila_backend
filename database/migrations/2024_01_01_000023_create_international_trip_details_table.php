<?php

use App\Constants\Direction;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('international_trip_details', function (Blueprint $table) {
            $table->id();
            $table->enum('direction', Direction::all());
            $table->string('starting_place')->nullable();
            $table->timestamp('starting_time');
            $table->timestamp('arrival_time')->nullable();
            $table->integer('total_seats');
            $table->decimal('seat_price', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('international_trip_details');
    }
};