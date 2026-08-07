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
        Schema::create('payout_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->decimal('amount', 15, 2);
            $table->string('method');
            $table->timestamps();
        });

        Schema::create('platform_commissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id');
            $table->unsignedBigInteger('platform_admin_id');
            $table->decimal('amount', 15, 2);
            $table->decimal('commission', 15, 2);
            $table->timestamps();
        });

        Schema::create('user_payout_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('method_type');
            $table->string('account_name');
            $table->string('account_number');
            $table->string('bank_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_payout_details');
        Schema::dropIfExists('platform_commissions');
        Schema::dropIfExists('payout_transactions');
    }
};
