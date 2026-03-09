<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lost_and_founds', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

            $table->string('finder_type')->after('id');
            $table->unsignedBigInteger('finder_id')->after('finder_type');
            $table->index(['finder_type', 'finder_id']);
        });
    }

    public function down(): void
    {
        Schema::table('lost_and_founds', function (Blueprint $table) {
            $table->dropIndex(['finder_type', 'finder_id']);
            $table->dropColumn(['finder_type', 'finder_id']);

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
        });
    }
};
