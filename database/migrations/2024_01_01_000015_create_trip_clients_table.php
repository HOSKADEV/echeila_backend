<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trip_clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('client_id');
            $table->string('client_type');
            $table->integer('number_of_seats');
            $table->decimal('total_fees', 10, 2);
            $table->text('note')->nullable();
            $table->timestamps();
            
            $table->index(['client_id', 'client_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('trip_clients');
    }
};