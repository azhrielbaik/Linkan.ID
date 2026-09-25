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
        Schema::create('email_change_otps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('new_email');
            $table->string('otp_hash');             // OTP disimpan dalam bentuk hash
            $table->unsignedTinyInteger('attempts')->default(0); // Hitung percobaan salah
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->unique('user_id');              // 1 user hanya boleh punya 1 OTP aktif
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_change_otps');
    }
};
