<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->string('canceled_by_type')->nullable()->after('status');
            $table->unsignedBigInteger('canceled_by_id')->nullable()->after('canceled_by_type');
            $table->string('cancellation_reason')->nullable()->after('canceled_by_id');
            $table->text('cancellation_note')->nullable()->after('cancellation_reason');
        });
    }

    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn(['canceled_by_type', 'canceled_by_id', 'cancellation_reason', 'cancellation_note']);
        });
    }
};
