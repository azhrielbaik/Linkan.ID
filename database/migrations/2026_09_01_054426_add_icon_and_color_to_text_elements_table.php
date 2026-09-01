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
        Schema::table('text_elements', function (Blueprint $table) {
            $table->string('button_icon_type')->nullable(); // 'upload', 'url', 'emoji'
            $table->string('button_icon_value')->nullable(); // filename, url, or emoji character
            $table->string('button_color')->nullable(); // hex color string e.g., #ff0000
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('text_elements', function (Blueprint $table) {
            $table->dropColumn(['button_icon_type', 'button_icon_value', 'button_color']);
        });
    }
};
