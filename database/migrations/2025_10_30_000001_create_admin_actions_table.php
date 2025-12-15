<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admin_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade');
            $table->string('action_type'); // wallet_charge, withdraw_sum, purchase_subscription, change_user_status, change_driver_status
            $table->morphs('target'); // target_id and target_type (Driver, Passenger, User)
            $table->json('old_values')->nullable(); // Store old values before change
            $table->json('new_values')->nullable(); // Store new values after change
            $table->decimal('amount', 10, 2)->nullable(); // For wallet operations
            $table->text('note')->nullable(); // Optional note/reason
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_actions');
    }
};
