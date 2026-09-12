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
        // 1. platform_commissions
        Schema::table('platform_commissions', function (Blueprint $table) {
            $table->index(['seller_id', 'created_at'], 'commissions_seller_created_idx');
            $table->index('created_at', 'commissions_created_at_idx');
        });

        // 2. payout_transactions
        Schema::table('payout_transactions', function (Blueprint $table) {
            $table->index(['status', 'created_at'], 'payouts_status_created_idx');
            $table->index('user_id', 'payouts_user_id_idx');
        });

        // 3. transactions
        Schema::table('transactions', function (Blueprint $table) {
            $table->index(['status', 'created_at'], 'transactions_status_created_idx');
        });

        // 4. digital_products
        Schema::table('digital_products', function (Blueprint $table) {
            $table->index(['verification_status', 'is_active'], 'products_verification_active_idx');
            $table->index(['verification_status', 'created_at'], 'products_verification_created_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('digital_products', function (Blueprint $table) {
            $table->dropIndex('products_verification_active_idx');
            $table->dropIndex('products_verification_created_idx');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex('transactions_status_created_idx');
        });

        Schema::table('payout_transactions', function (Blueprint $table) {
            $table->dropIndex('payouts_status_created_idx');
            $table->dropIndex('payouts_user_id_idx');
        });

        Schema::table('platform_commissions', function (Blueprint $table) {
            $table->dropIndex('commissions_seller_created_idx');
            $table->dropIndex('commissions_created_at_idx');
        });
    }
};
