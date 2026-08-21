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
        Schema::table('digital_products', function (Blueprint $table) {
            if (!Schema::hasColumn('digital_products', 'takedown_reason')) {
                $table->text('takedown_reason')->nullable()->after('is_active');
            }
            if (!Schema::hasColumn('digital_products', 'takedown_at')) {
                $table->timestamp('takedown_at')->nullable()->after('takedown_reason');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('digital_products', function (Blueprint $table) {
            if (Schema::hasColumn('digital_products', 'takedown_reason')) {
                $table->dropColumn('takedown_reason');
            }
            if (Schema::hasColumn('digital_products', 'takedown_at')) {
                $table->dropColumn('takedown_at');
            }
        });
    }
};
