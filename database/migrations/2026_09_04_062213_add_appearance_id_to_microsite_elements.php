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
            $table->string('alias')->nullable()->unique()->after('user_id');
        });

        $elementTables = [
            'image_elements',
            'text_elements',
            'divider_elements',
            'video_elements',
            'social_media_elements'
        ];

        foreach ($elementTables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->foreignId('appearance_id')->nullable()->after('user_id')->constrained('appearances')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $elementTables = [
            'image_elements',
            'text_elements',
            'divider_elements',
            'video_elements',
            'social_media_elements'
        ];

        foreach ($elementTables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropForeign(['appearance_id']);
                $table->dropColumn('appearance_id');
            });
        }

        Schema::table('appearances', function (Blueprint $table) {
            $table->dropColumn('alias');
        });
    }
};
