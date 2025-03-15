<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add a unique constraint on name and category_id
        Schema::table('specification_types', function (Blueprint $table) {
            $table->unique(['name', 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the unique constraint
        Schema::table('specification_types', function (Blueprint $table) {
            $table->dropUnique(['name', 'category_id']);
        });
    }
};
