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
            if (!Schema::hasColumn('users', 'theme')) {
                $table->string('theme')->nullable()->default('light')->after('role');
            }
            if (!Schema::hasColumn('users', 'theme_color')) {
                $table->string('theme_color', 20)->nullable()->default('#ed842c')->after('theme');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('users', 'theme')) {
                $columns[] = 'theme';
            }
            if (Schema::hasColumn('users', 'theme_color')) {
                $columns[] = 'theme_color';
            }
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
