<?php

use App\Constants\VehicleType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('paid_driving_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('starting_point_id')->constrained('locations')->onDelete('cascade');
            $table->foreignId('arrival_point_id')->constrained('locations')->onDelete('cascade');
            $table->timestamp('starting_time');
            $table->enum('vehicle_type', VehicleType::all());
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('paid_driving_details');
    }
};