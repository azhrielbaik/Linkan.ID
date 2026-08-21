<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tabel Platform Settings
        Schema::create('platform_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Seed default settings
        DB::table('platform_settings')->insert([
            [
                'key' => 'platform_commission_percent',
                'value' => '5',
                'description' => 'Persentase potongan komisi platform per penarikan dana seller (%)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'min_withdraw_amount',
                'value' => '10000',
                'description' => 'Batas minimum nominal penarikan dana (withdraw) seller (IDR)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 2. Tabel Broadcast Announcements
        Schema::create('broadcast_announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('message');
            $table->enum('type', ['info', 'warning', 'success', 'danger'])->default('info');
            $table->string('target_role')->default('all_sellers');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('broadcast_announcements');
        Schema::dropIfExists('platform_settings');
    }
};
