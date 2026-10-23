<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Fix unique constraints agar kompatibel dengan SoftDeletes.
 *
 * Masalah: kolom email, username, dan google_id memiliki unique constraint
 * biasa yang tidak mempertimbangkan deleted_at. Akibatnya, user yang sudah
 * melakukan soft-delete akunnya tidak bisa mendaftar ulang dengan email/
 * username yang sama karena DB masih menganggap data tersebut "used".
 *
 * Solusi: Ganti unique index biasa dengan partial unique index
 * (WHERE deleted_at IS NULL) sehingga hanya baris aktif yang dievaluasi.
 *
 * Catatan: Partial index didukung oleh PostgreSQL dan SQLite (>=3.8.9).
 * Untuk MySQL/MariaDB, partial index tidak didukung secara native — solusi
 * yang dipakai adalah pendekatan anonymize-on-delete di AccountService,
 * sehingga migration ini hanya menjadi lapisan keamanan tambahan di DB
 * yang mendukungnya.
 */
return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if (in_array($driver, ['pgsql', 'sqlite'])) {
            // Hapus unique index lama jika ada
            if ($driver === 'sqlite') {
                DB::statement('DROP INDEX IF EXISTS users_email_unique');
                DB::statement('DROP INDEX IF EXISTS users_username_unique');
                DB::statement('DROP INDEX IF EXISTS users_google_id_unique');
            } else {
                $existingIndexes = collect(Schema::getIndexes('users'))->pluck('name')->all();
                Schema::table('users', function (Blueprint $table) use ($existingIndexes) {
                    if (in_array('users_email_unique', $existingIndexes)) {
                        $table->dropUnique('users_email_unique');
                    }
                    if (in_array('users_username_unique', $existingIndexes)) {
                        $table->dropUnique('users_username_unique');
                    }
                    if (in_array('users_google_id_unique', $existingIndexes)) {
                        $table->dropUnique('users_google_id_unique');
                    }
                });
            }

            // Buat partial unique index (hanya untuk baris non-deleted)
            DB::statement('CREATE UNIQUE INDEX users_email_unique_active ON users (email) WHERE deleted_at IS NULL');
            DB::statement('CREATE UNIQUE INDEX users_username_unique_active ON users (username) WHERE deleted_at IS NULL');
            DB::statement('CREATE UNIQUE INDEX users_google_id_unique_active ON users (google_id) WHERE deleted_at IS NULL AND google_id IS NOT NULL');
        }

        // Untuk MySQL/MariaDB: partial index tidak didukung.
        // Strategi anonymize-on-delete di AccountService::deleteAccount()
        // sudah cukup sebagai mitigasi utama.
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if (in_array($driver, ['pgsql', 'sqlite'])) {
            // Hapus partial index
            DB::statement('DROP INDEX IF EXISTS users_email_unique_active');
            DB::statement('DROP INDEX IF EXISTS users_username_unique_active');
            DB::statement('DROP INDEX IF EXISTS users_google_id_unique_active');

            // Kembalikan unique index biasa
            Schema::table('users', function (Blueprint $table) {
                $table->unique('email');
                $table->unique('username');
            });
        }
    }
};
