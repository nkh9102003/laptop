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
        // Create specification types table
        Schema::create('specification_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('display_name');
            $table->string('unit')->nullable(); // e.g., GB, MHz, inches
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Create product specifications table (for key-value pairs)
        Schema::create('product_specifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('specification_type_id')->constrained()->onDelete('cascade');
            $table->text('value');
            $table->timestamps();
            
            // Ensure each product has only one value per specification type
            $table->unique(['product_id', 'specification_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_specifications');
        Schema::dropIfExists('specification_types');
    }
}; 