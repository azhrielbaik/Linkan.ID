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
        Schema::table('image_elements', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('order_position');
        });
        Schema::table('divider_elements', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('order_position');
        });
        Schema::table('text_elements', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('order_position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('microsite_elements_tables', function (Blueprint $table) {
            //
        });
    }
};
