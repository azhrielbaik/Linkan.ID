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
        Schema::table('users', function (Blueprint $table) {
            $columnsToDrop = [];

            if (Schema::hasColumn('users', 'theme')) {
                $columnsToDrop[] = 'theme';
            }

            if (Schema::hasColumn('users', 'theme_color')) {
                $columnsToDrop[] = 'theme_color';
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'theme')) {
                $table->string('theme')->nullable()->default('light')->after('role');
            }

            if (!Schema::hasColumn('users', 'theme_color')) {
                $table->string('theme_color', 20)->nullable()->default('#ed842c')->after('theme');
            }
        });
    }
};
