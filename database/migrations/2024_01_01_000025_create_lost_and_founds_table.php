<?php

use App\Constants\LostAndFoundStatus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lost_and_founds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('passenger_id')->constrained()->onDelete('cascade');
            $table->text('description');
            $table->enum('status', LostAndFoundStatus::all())->default(LostAndFoundStatus::default());
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lost_and_founds');
    }
};
