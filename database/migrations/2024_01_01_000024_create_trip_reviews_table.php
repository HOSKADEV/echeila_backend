<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trip_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->onDelete('cascade');
            $table->morphs('reviewer'); // Creates reviewer_id and reviewer_type
            $table->morphs('reviewee'); // Creates reviewee_id and reviewee_type
            $table->integer('rating')->comment('Range 1-5');
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trip_reviews');
    }
};
