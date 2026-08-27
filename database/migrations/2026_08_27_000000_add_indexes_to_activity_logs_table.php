<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->index('action', 'activity_logs_action_index');
            $table->index('created_at', 'activity_logs_created_at_index');
            $table->index('user_id', 'activity_logs_user_id_index');
        });
    }

    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex('activity_logs_action_index');
            $table->dropIndex('activity_logs_created_at_index');
            $table->dropIndex('activity_logs_user_id_index');
        });
    }
};
