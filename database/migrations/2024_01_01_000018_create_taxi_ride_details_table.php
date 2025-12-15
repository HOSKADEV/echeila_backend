<?php

use App\Constants\RideType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('taxi_ride_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('starting_point_id');
            $table->string('starting_point_type');
            $table->unsignedBigInteger('arrival_point_id');
            $table->string('arrival_point_type');
            $table->enum('ride_type', RideType::all());
            $table->timestamps();
            
            $table->index(['starting_point_id', 'starting_point_type']);
            $table->index(['arrival_point_id', 'arrival_point_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('taxi_ride_details');
    }
};