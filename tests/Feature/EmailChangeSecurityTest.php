<?php

namespace Tests\Feature;

use App\Models\PendingEmailChange;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class EmailChangeSecurityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test warning Google muncul di form ganti email jika user terhubung ke Google
     */
    public function test_email_change_form_shows_warning_when_google_is_connected(): void
    {
        $user = User::factory()->create([
            'role' => 'admin_seller',
            'google_id' => 'google-test-id-123',
        ]);

        $response = $this->actingAs($user)->get(route('admin.account'));

        $response->assertOk();
        $response->assertSee('Perhatian:');
        $response->assertSee('Akun Anda saat ini terhubung dengan Google. Jika Anda mengganti email, koneksi Google akan otomatis diputuskan dan Anda perlu menghubungkannya kembali di menu Layanan Terhubung.');
    }

    /**
     * Test warning Google TIDAK muncul jika user tidak terhubung ke Google
     */
    public function test_email_change_form_hides_warning_when_google_is_not_connected(): void
    {
        $user = User::factory()->create([
            'role' => 'admin_seller',
            'google_id' => null,
        ]);

        $response = $this->actingAs($user)->get(route('admin.account'));

        $response->assertOk();
        $response->assertDontSee('Perhatian: Akun Anda saat ini terhubung dengan Google. Jika Anda mengganti email, koneksi Google akan otomatis diputuskan dan Anda perlu menghubungkannya kembali di menu Layanan Terhubung.');
    }

    /**
     * Test saat email berhasil diverifikasi, koneksi Google otomatis diputuskan dan dicatat di ActivityLog
     */
    public function test_google_is_disconnected_automatically_on_successful_email_change(): void
    {
        $user = User::factory()->create([
            'email' => 'original@linkan.id',
            'google_id' => 'google-test-id-123',
            'role' => 'admin_seller',
        ]);

        $token = Str::random(64);
        $newEmail = 'brandnew@linkan.id';

        PendingEmailChange::create([
            'user_id' => $user->id,
            'new_email' => $newEmail,
            'token' => $token,
            'expires_at' => now()->addHours(24),
        ]);

        $response = $this->actingAs($user)->get(route('admin.account.email.verify', ['token' => $token]));

        $response->assertRedirect(route('admin.account'));
        $response->assertSessionHas('success', 'Alamat email akun Anda berhasil diperbarui menjadi '.$newEmail.'. Koneksi Google Anda telah diputuskan secara otomatis karena email akun berubah. Silakan hubungkan kembali di halaman Layanan Terhubung jika diperlukan.');

        $user->refresh();
        $this->assertSame($newEmail, $user->email);
        $this->assertNull($user->google_id);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => 'google_disconnected_on_email_change',
        ]);
    }

    /**
     * Test jika user sebelumnya tidak terhubung Google, event google_disconnected_on_email_change tidak dicatat
     */
    public function test_regular_email_change_does_not_log_google_disconnection(): void
    {
        $user = User::factory()->create([
            'email' => 'normal@linkan.id',
            'google_id' => null,
            'role' => 'admin_seller',
        ]);

        $token = Str::random(64);
        $newEmail = 'normalnew@linkan.id';

        PendingEmailChange::create([
            'user_id' => $user->id,
            'new_email' => $newEmail,
            'token' => $token,
            'expires_at' => now()->addHours(24),
        ]);

        $response = $this->actingAs($user)->get(route('admin.account.email.verify', ['token' => $token]));

        $response->assertRedirect(route('admin.account'));
        $response->assertSessionHas('success', 'Alamat email akun Anda berhasil diperbarui menjadi '.$newEmail.'.');

        $user->refresh();
        $this->assertSame($newEmail, $user->email);
        $this->assertNull($user->google_id);

        $this->assertDatabaseMissing('activity_logs', [
            'user_id' => $user->id,
            'action' => 'google_disconnected_on_email_change',
        ]);
    }

    /**
     * Test permintaan pertama ganti email berhasil dan permintaan kedua dalam 24 jam diblokir
     */
    public function test_first_email_change_request_succeeds_and_second_is_throttled_by_cooldown(): void
    {
        \Illuminate\Support\Facades\Mail::fake();

        $user = User::factory()->create([
            'email' => 'seller1@linkan.id',
            'role' => 'admin_seller',
        ]);

        // Request pertama
        $response1 = $this->actingAs($user)->post(route('admin.account.email.request-otp'), [
            'new_email' => 'newemail1@linkan.id',
        ]);

        $response1->assertRedirect(route('admin.account'));
        $response1->assertSessionHas('otp_sent');

        // Simulasikan selesai verifikasi OTP sehingga last_email_change_requested_at aktif
        $user->last_email_change_requested_at = now();
        $user->save();
        \Illuminate\Support\Facades\RateLimiter::hit('email_change|'.$user->id, 86400);

        // Request kedua dalam waktu 24 jam berturut-turut harus diblokir oleh rate limiter
        $response2 = $this->actingAs($user)->post(route('admin.account.email.request-otp'), [
            'new_email' => 'newemail2@linkan.id',
        ]);

        $response2->assertSessionHasErrors(['new_email']);
        $errors = session('errors')->get('new_email');
        $this->assertStringContainsString('Anda sudah melakukan permintaan ganti email dalam 24 jam terakhir', $errors[0]);
    }

    /**
     * Test fallback database bekerja jika cache RateLimiter ter-clear
     */
    public function test_email_change_is_blocked_by_database_tracking_fallback(): void
    {
        \Illuminate\Support\Facades\Mail::fake();

        $user = User::factory()->create([
            'email' => 'seller2@linkan.id',
            'role' => 'admin_seller',
            'last_email_change_requested_at' => now()->subHours(2), // 2 jam yang lalu
        ]);

        // Pastikan cache RateLimiter bersih
        \Illuminate\Support\Facades\RateLimiter::clear('email_change|'.$user->id);

        $response = $this->actingAs($user)->post(route('admin.account.email.request-otp'), [
            'new_email' => 'newemail3@linkan.id',
        ]);

        $response->assertSessionHasErrors(['new_email']);
        $errors = session('errors')->get('new_email');
        $this->assertStringContainsString('Anda sudah melakukan permintaan ganti email dalam 24 jam terakhir', $errors[0]);
    }

    /**
     * Test tampilan info box pada view saat cooldown tidak aktif
     */
    public function test_view_shows_info_box_and_enabled_button_when_cooldown_inactive(): void
    {
        $user = User::factory()->create([
            'role' => 'admin_seller',
            'last_email_change_requested_at' => null,
        ]);

        $response = $this->actingAs($user)->get(route('admin.account'));

        $response->assertOk();
        $response->assertSee('Cara Kerja Verifikasi');
        $response->assertSee('Kirim Kode OTP ke Email Saya');
        $response->assertDontSee('Tidak dapat mengganti email saat ini. Anda telah melakukan permintaan pergantian email dalam 24 jam terakhir.');
    }

    /**
     * Test tampilan warning box amber dan tombol disabled pada view saat cooldown aktif
     */
    public function test_view_shows_amber_warning_box_and_disabled_button_when_cooldown_active(): void
    {
        $user = User::factory()->create([
            'role' => 'admin_seller',
            'last_email_change_requested_at' => now()->subHours(5),
        ]);

        $response = $this->actingAs($user)->get(route('admin.account'));

        $response->assertOk();
        $response->assertSee('Tidak dapat mengganti email saat ini.');
        $response->assertSee('Anda telah melakukan permintaan pergantian email dalam 24 jam terakhir. Silakan coba lagi nanti.');
        $response->assertSee('disabled', false);
    }
}
