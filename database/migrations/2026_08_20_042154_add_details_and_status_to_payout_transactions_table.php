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
        Schema::table('payout_transactions', function (Blueprint $table) {
            $table->decimal('gross_amount', 15, 2)->nullable()->after('amount');
            $table->decimal('commission', 15, 2)->default(0)->after('gross_amount');
            $table->string('account_name')->nullable()->after('method');
            $table->string('account_number')->nullable()->after('account_name');
            $table->string('bank_name')->nullable()->after('account_number');
            $table->string('status', 50)->default('pending')->after('bank_name'); // pending, approved, rejected
            $table->text('rejection_reason')->nullable()->after('status');
            $table->timestamp('processed_at')->nullable()->after('rejection_reason');
            $table->unsignedBigInteger('processed_by')->nullable()->after('processed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payout_transactions', function (Blueprint $table) {
            $table->dropColumn([
                'gross_amount',
                'commission',
                'account_name',
                'account_number',
                'bank_name',
                'status',
                'rejection_reason',
                'processed_at',
                'processed_by'
            ]);
        });
    }
};
