<?php

use App\Constants\WaterType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('water_transport_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_point_id')->constrained('locations')->onDelete('cascade');
            $table->timestamp('delivery_time');
            $table->enum('water_type', WaterType::all());
            $table->decimal('quantity', 8, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('water_transport_details');
    }
};