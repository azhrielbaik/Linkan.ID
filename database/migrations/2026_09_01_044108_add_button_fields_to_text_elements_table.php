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
            $table->boolean('has_button')->default(false);
            $table->string('button_text')->nullable();
            $table->string('button_link')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('text_elements', function (Blueprint $table) {
            $table->dropColumn(['has_button', 'button_text', 'button_link']);
        });
    }
};
