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
        Schema::table('link_views', function (Blueprint $table) {
            $table->index(['user_id', 'created_at'], 'link_views_user_created_idx');
            $table->index(['link_id', 'created_at'], 'link_views_link_created_idx');
        });

        Schema::table('link_clicks', function (Blueprint $table) {
            $table->index(['user_id', 'created_at'], 'link_clicks_user_created_idx');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->index(['product_id', 'status'], 'transactions_product_status_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('link_views', function (Blueprint $table) {
            $table->dropIndex('link_views_user_created_idx');
            $table->dropIndex('link_views_link_created_idx');
        });

        Schema::table('link_clicks', function (Blueprint $table) {
            $table->dropIndex('link_clicks_user_created_idx');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex('transactions_product_status_idx');
        });
    }
};

