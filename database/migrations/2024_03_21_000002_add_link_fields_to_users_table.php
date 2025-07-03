<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('name'); // untuk URL unik user (linkan.id/username)
            $table->string('custom_link')->unique()->nullable()->after('username'); // jika user ingin custom link
            $table->boolean('is_link_active')->default(true)->after('custom_link');
            $table->text('bio')->nullable()->after('is_link_active'); // deskripsi profil user
            $table->string('avatar')->nullable()->after('bio'); // foto profil user
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username',
                'custom_link',
                'is_link_active',
                'bio',
                'avatar'
            ]);
        });
    }
}; 