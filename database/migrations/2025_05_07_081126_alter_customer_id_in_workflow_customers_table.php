<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Convert column type using raw SQL
        DB::statement('ALTER TABLE workflow_customers ALTER COLUMN customer_id TYPE BIGINT USING customer_id::bigint');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE workflow_customers ALTER COLUMN customer_id TYPE VARCHAR(255)');
    }

};
