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
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('flash_sale_id')->nullable()->after('product_id')->constrained()->nullOnDelete();
            $table->decimal('original_price', 10, 2)->nullable()->after('price')->comment('Original product price before discount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['flash_sale_id']);
            $table->dropColumn(['flash_sale_id', 'original_price']);
        });
    }
}; 