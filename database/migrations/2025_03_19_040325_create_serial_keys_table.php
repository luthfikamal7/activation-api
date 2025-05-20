<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('serial_keys', function (Blueprint $table) {
            $table->id();
            $table->string('serial_code')->unique();
            $table->boolean('is_used')->default(false);
            $table->string('validation_key')->nullable();
            $table->date('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('serial_keys');
    }
};


