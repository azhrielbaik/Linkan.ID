<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shortlinks', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // nama pendek
            $table->text('destination'); // link tujuan asli
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shortlinks');
    }
};
