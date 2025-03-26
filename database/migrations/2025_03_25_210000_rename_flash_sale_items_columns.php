<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('flash_sale_items', function (Blueprint $table) {
            $table->renameColumn('discount_price', 'sale_price');
            $table->renameColumn('quantity_limit', 'max_quantity');
        });
    }

    public function down()
    {
        Schema::table('flash_sale_items', function (Blueprint $table) {
            $table->renameColumn('sale_price', 'discount_price');
            $table->renameColumn('max_quantity', 'quantity_limit');
        });
    }
};