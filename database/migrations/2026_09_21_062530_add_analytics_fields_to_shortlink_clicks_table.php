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
        Schema::table('shortlink_clicks', function (Blueprint $table) {
            $table->string('visitor_id')->nullable()->after('referer')->index();
            $table->string('country')->nullable()->after('ip_address');
            $table->string('city')->nullable()->after('country');
            $table->string('device_type')->nullable()->after('city'); // mobile, tablet, desktop
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shortlink_clicks', function (Blueprint $table) {
            $table->dropColumn(['visitor_id', 'country', 'city', 'device_type']);
        });
    }
};
