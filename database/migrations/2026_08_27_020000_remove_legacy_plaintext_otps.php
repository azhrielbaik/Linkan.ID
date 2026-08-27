<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('password_reset_requests')->update(['otp_code' => null]);
    }

    public function down(): void
    {
        // Plaintext OTP values are intentionally not restored.
    }
};
