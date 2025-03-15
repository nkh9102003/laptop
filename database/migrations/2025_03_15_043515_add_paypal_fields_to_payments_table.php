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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('transaction_id')->nullable()->after('payment_method');
            $table->string('payer_id')->nullable()->after('transaction_id');
            $table->string('payer_email')->nullable()->after('payer_id');
            $table->string('status')->nullable()->after('payer_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['transaction_id', 'payer_id', 'payer_email', 'status']);
        });
    }
}; 