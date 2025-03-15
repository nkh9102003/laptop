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
        // Create specification categories table
        Schema::create('specification_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');         // Internal name like "processor"
            $table->string('display_name'); // Display name like "Processor"
            $table->text('description')->nullable();
            $table->integer('display_order')->default(0); // Order for display
            $table->timestamps();
        });
        
        // Add category_id to specification_types table
        Schema::table('specification_types', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('id')->constrained('specification_categories')->onDelete('set null');
            $table->integer('display_order')->default(0)->after('description'); // Order within category
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('specification_types', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['category_id', 'display_order']);
        });
        
        Schema::dropIfExists('specification_categories');
    }
}; 