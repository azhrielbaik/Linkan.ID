<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('password_reset_requests', function (Blueprint $table) {
            $table->string('reset_token_hash', 64)->nullable()->unique()->after('email');
            $table->string('otp_hash', 255)->nullable()->after('otp_code');
            $table->unsignedTinyInteger('attempts')->default(0)->after('otp_code');
            $table->timestamp('used_at')->nullable()->after('resolved_at');
        });
    }

    public function down(): void
    {
        Schema::table('password_reset_requests', function (Blueprint $table) {
            $table->dropUnique(['reset_token_hash']);
            $table->dropColumn(['reset_token_hash', 'otp_hash', 'attempts', 'used_at']);
        });
    }
};
