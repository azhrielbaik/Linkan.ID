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
        if (! Schema::hasColumn('link_views', 'user_agent')) {
            Schema::table('link_views', function (Blueprint $table) {
                $table->text('user_agent')->nullable()->after('ip_address');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('link_views', 'user_agent')) {
            Schema::table('link_views', function (Blueprint $table) {
                $table->dropColumn('user_agent');
            });
        }
    }
};
