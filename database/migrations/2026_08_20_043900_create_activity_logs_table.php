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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // Admin ID
            $table->string('action', 100); // e.g. approve_product, reject_payout, suspend_user
            $table->text('description'); // Penjelasan aktivitas
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('properties')->nullable(); // Metadata ekstra (target_id, data, etc.)
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
