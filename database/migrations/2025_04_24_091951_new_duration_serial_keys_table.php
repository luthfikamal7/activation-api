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
        Schema::table('serial_keys', function (Blueprint $table) {
            $table->integer('duration')->default(1)->after('is_used'); // Duration in years
            $table->date('start_at')->nullable()->after('expires_at'); // Activation start date
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('serial_keys', function (Blueprint $table) {
            $table->dropColumn(['duration', 'start_at']);
        });
    }
};
