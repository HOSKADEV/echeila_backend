<?php

use App\Constants\TripStatus;
use App\Constants\TripType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained()->onDelete('cascade');
            $table->string('identifier')->unique();
            $table->enum('type', TripType::all());
            $table->enum('status', TripStatus::all())->default(TripStatus::PENDING);
            $table->text('note')->nullable();
            $table->unsignedBigInteger('detailable_id')->nullable();
            $table->string('detailable_type')->nullable();
            $table->timestamps();
            
            $table->index(['detailable_id', 'detailable_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('trips');
    }
};