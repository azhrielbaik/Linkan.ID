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
        // 1. Tambahkan kolom suspended_until dan suspend_reason ke tabel users
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'suspended_until')) {
                $table->timestamp('suspended_until')->nullable()->after('suspended_at');
            }
            if (!Schema::hasColumn('users', 'suspend_reason')) {
                $table->text('suspend_reason')->nullable()->after('suspended_until');
            }
        });

        // 2. Buat tabel suspension_appeals untuk banding seller
        if (!Schema::hasTable('suspension_appeals')) {
            Schema::create('suspension_appeals', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->text('appeal_reason');
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->text('admin_notes')->nullable();
                $table->timestamp('resolved_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suspension_appeals');

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'suspend_reason')) {
                $table->dropColumn('suspend_reason');
            }
            if (Schema::hasColumn('users', 'suspended_until')) {
                $table->dropColumn('suspended_until');
            }
        });
    }
};
