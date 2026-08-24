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
        Schema::table('broadcast_announcements', function (Blueprint $table) {
            $table->boolean('send_email')->default(false)->after('is_active');
            $table->integer('emails_sent_count')->default(0)->after('send_email');
            $table->timestamp('email_sent_at')->nullable()->after('emails_sent_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('broadcast_announcements', function (Blueprint $table) {
            $table->dropColumn(['send_email', 'emails_sent_count', 'email_sent_at']);
        });
    }
};
