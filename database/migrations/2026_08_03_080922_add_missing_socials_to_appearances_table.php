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
        Schema::table('appearances', function (Blueprint $table) {
            if (!Schema::hasColumn('appearances', 'instagram')) {
                $table->string('instagram')->nullable();
            }
            if (!Schema::hasColumn('appearances', 'tiktok')) {
                $table->string('tiktok')->nullable();
            }
            if (!Schema::hasColumn('appearances', 'whatsapp')) {
                $table->string('whatsapp')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appearances', function (Blueprint $table) {
            $table->dropColumn(['instagram', 'tiktok', 'whatsapp']);
        });
    }
};
