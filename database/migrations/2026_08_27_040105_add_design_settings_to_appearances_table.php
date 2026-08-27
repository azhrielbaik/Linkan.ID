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
            // Tata letak profil di microsite: classic, left-aligned, card, minimal
            $table->string('profile_layout')->default('classic')->after('profile_shape');

            // Bentuk sudut blok elemen di microsite: sharp, rounded, pill
            $table->string('block_shape')->default('rounded')->after('profile_layout');

            // Jenis background: 'color' (warna solid) atau 'image' (gambar)
            $table->string('background_type')->default('color')->after('background_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appearances', function (Blueprint $table) {
            $table->dropColumn(['profile_layout', 'block_shape', 'background_type']);
        });
    }
};
