<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify transactions.status enum to allow 'disputed' and 'refunded'
        if (Schema::hasTable('transactions')) {
            try {
                DB::statement("ALTER TABLE `transactions` MODIFY COLUMN `status` ENUM('pending', 'success', 'failed', 'disputed', 'refunded') NOT NULL DEFAULT 'pending'");
            } catch (\Throwable $e) {
                // Fallback for non-MySQL or platforms where MODIFY statement format differs
            }
        }

        Schema::create('disputes', function (Blueprint $table) {
            $table->id();
            $table->string('dispute_code')->unique();
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->string('order_id')->index();
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('digital_products')->onDelete('cascade');
            $table->string('buyer_name');
            $table->string('buyer_email')->index();
            $table->string('buyer_phone')->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('reason'); // broken_link, corrupted_file, misleading_description, fraud_scam, other
            $table->text('description');
            $table->string('evidence_file')->nullable();
            $table->string('refund_bank_name')->nullable();
            $table->string('refund_account_name')->nullable();
            $table->string('refund_account_number')->nullable();
            $table->enum('status', ['pending', 'under_review', 'resolved_refunded', 'resolved_rejected'])->default('pending')->index();
            $table->text('admin_notes')->nullable();
            $table->string('refund_reference')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disputes');

        if (Schema::hasTable('transactions')) {
            try {
                DB::statement("ALTER TABLE `transactions` MODIFY COLUMN `status` ENUM('pending', 'success', 'failed') NOT NULL DEFAULT 'pending'");
            } catch (\Throwable $e) {
                // Ignore rollback failure on enum revert
            }
        }
    }
};
