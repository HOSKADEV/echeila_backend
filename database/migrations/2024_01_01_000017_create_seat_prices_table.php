<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('seat_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('starting_wilaya_id')->constrained('wilayas')->onDelete('cascade');
            $table->foreignId('arrival_wilaya_id')->constrained('wilayas')->onDelete('cascade');
            $table->decimal('default_seat_price', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('seat_prices');
    }
};