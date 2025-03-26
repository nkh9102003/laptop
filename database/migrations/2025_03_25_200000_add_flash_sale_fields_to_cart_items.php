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
        Schema::table('cart_items', function (Blueprint $table) {
            $table->unsignedBigInteger('flash_sale_id')->nullable()->after('quantity');
            $table->decimal('flash_sale_price', 10, 2)->nullable()->after('flash_sale_id');
            
            $table->foreign('flash_sale_id')->references('id')->on('flash_sales')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['flash_sale_id']);
            $table->dropColumn(['flash_sale_id', 'flash_sale_price']);
        });
    }
}; 