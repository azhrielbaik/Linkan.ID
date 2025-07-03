<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('appearances', function (Blueprint $table) {
            $table->string('linkedin')->nullable();
            $table->string('facebook')->nullable();
            $table->string('website')->nullable();
            $table->string('twitter')->nullable();
            $table->string('youtube')->nullable();
            $table->string('telegram')->nullable();
            $table->string('email')->nullable();
            $table->string('discord')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('appearances', function (Blueprint $table) {
            $table->dropColumn([
                'linkedin',
                'facebook',
                'website',
                'twitter',
                'youtube',
                'telegram',
                'email',
                'discord',
            ]);
        });
    }
};
