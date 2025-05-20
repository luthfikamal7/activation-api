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
            $table->unsignedBigInteger('customer_id')->nullable()->after('duration');
            $table->unsignedBigInteger('workflow_id')->nullable()->after('customer_id');

            // Optional: Add foreign keys if needed
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            $table->foreign('workflow_id')->references('id')->on('workflow_customers')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('serial_keys', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['workflow_id']);
            $table->dropColumn(['customer_id', 'workflow_id']);
        });
    }

};
