<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {

        // Rename the column in the new table
        Schema::table('projects', function (Blueprint $table) {
            $table->renameColumn('workflow_id', 'project_id');
        });
    }

    public function down(): void
    {
        // Revert the column name
        Schema::table('projects', function (Blueprint $table) {
            $table->renameColumn('project_id', 'workflow_id');
        });
    }
};

