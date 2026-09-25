<?php

namespace Tests\Feature;

use App\Mail\EmailChangeOtpMail;
use App\Mail\EmailChangeVerificationMail;
use App\Models\EmailChangeOtp;
use App\Models\PendingEmailChange;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class EmailChangeOtpTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test tamu tidak dapat meminta OTP ganti email.
     */
    public function test_guest_cannot_request_email_change_otp(): void
    {
        $response = $this->post(route('admin.account.email.request-otp'), [
            'new_email' => 'new@example.com',
        ]);

        $response->assertRedirect(route('login'));
    }

    /**
     * Test validasi email baru saat meminta OTP.
     */
    public function test_request_otp_validates_new_email(): void
    {
        $user = User::factory()->create([
            'email' => 'current@example.com',
            'role' => 'admin_seller',
        ]);

        User::factory()->create([
            'email' => 'existing@example.com',
        ]);

        // Email kosong
        $response = $this->actingAs($user)->post(route('admin.account.email.request-otp'), [
            'new_email' => '',
        ]);
        $response->assertSessionHasErrors('new_email');

        // Format tidak valid
        $response = $this->actingAs($user)->post(route('admin.account.email.request-otp'), [
            'new_email' => 'not-an-email',
        ]);
        $response->assertSessionHasErrors('new_email');

        // Email sudah dipakai akun lain
        $response = $this->actingAs($user)->post(route('admin.account.email.request-otp'), [
            'new_email' => 'existing@example.com',
        ]);
        $response->assertSessionHasErrors('new_email');
    }

    /**
     * Test permintaan OTP berhasil: OTP disimpan, email dikirim ke email aktif, session diset.
     */
    public function test_request_otp_success_stores_record_and_sends_to_current_email(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'name' => 'Budi Seller',
            'email' => 'budi.active@example.com',
            'role' => 'admin_seller',
        ]);

        $newEmail = 'budi.new@example.com';

        $response = $this->actingAs($user)->post(route('admin.account.email.request-otp'), [
            'new_email' => $newEmail,
        ]);

        $response->assertRedirect(route('admin.account'));
        $response->assertSessionHas('otp_sent');
        $response->assertSessionHas('otp_target_email', $newEmail);

        // Pastikan record tersimpan di database dengan hash OTP
        $otpRecord = EmailChangeOtp::where('user_id', $user->id)->first();
        $this->assertNotNull($otpRecord);
        $this->assertSame($newEmail, $otpRecord->new_email);
        $this->assertSame(0, $otpRecord->attempts);
        $this->assertTrue($otpRecord->expires_at->isFuture());

        // Pastikan OTP dikirim ke email AKTIF saat ini, BUKAN email baru
        Mail::assertSent(EmailChangeOtpMail::class, function ($mail) use ($user, $newEmail, $otpRecord) {
            return $mail->hasTo($user->email)
                && ! $mail->hasTo($newEmail)
                && $mail->userName === $user->name
                && $mail->newEmail === $newEmail
                && Hash::check($mail->otp, $otpRecord->otp_hash);
        });

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => 'email_change_otp_requested',
        ]);
    }

    /**
     * Test permintaan OTP diblokir jika melebihi rate limit permintaan (3x per 15 menit).
     */
    public function test_request_otp_throttled_after_3_requests_in_15_minutes(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'throttle@example.com',
            'role' => 'admin_seller',
        ]);

        $key = 'otp_request|'.$user->id;
        RateLimiter::hit($key, 900);
        RateLimiter::hit($key, 900);
        RateLimiter::hit($key, 900);

        $response = $this->actingAs($user)->post(route('admin.account.email.request-otp'), [
            'new_email' => 'throttle.new@example.com',
        ]);

        $response->assertSessionHasErrors('new_email');
        Mail::assertNothingSent();
    }

    /**
     * Test permintaan OTP diblokir jika user sedang dalam cooldown 24 jam.
     */
    public function test_request_otp_blocked_during_24h_cooldown(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'cooldown@example.com',
            'role' => 'admin_seller',
            'last_email_change_requested_at' => Carbon::now()->subHours(5),
        ]);

        $response = $this->actingAs($user)->post(route('admin.account.email.request-otp'), [
            'new_email' => 'cooldown.new@example.com',
        ]);

        $response->assertSessionHasErrors('new_email');
        Mail::assertNothingSent();
    }

    /**
     * Test verifikasi OTP gagal jika tidak ada OTP aktif.
     */
    public function test_verify_otp_fails_when_no_active_otp(): void
    {
        $user = User::factory()->create([
            'role' => 'admin_seller',
        ]);

        $response = $this->actingAs($user)->post(route('admin.account.email.verify-otp'), [
            'otp' => '123456',
        ]);

        $response->assertSessionHasErrors('otp');
    }

    /**
     * Test verifikasi OTP gagal jika OTP sudah kedaluwarsa (>10 menit).
     */
    public function test_verify_otp_fails_when_expired(): void
    {
        $user = User::factory()->create([
            'role' => 'admin_seller',
        ]);

        EmailChangeOtp::create([
            'user_id' => $user->id,
            'new_email' => 'expired@example.com',
            'otp_hash' => Hash::make('123456'),
            'attempts' => 0,
            'expires_at' => Carbon::now()->subMinute(),
        ]);

        $response = $this->actingAs($user)->post(route('admin.account.email.verify-otp'), [
            'otp' => '123456',
        ]);

        $response->assertSessionHasErrors('otp');
        $this->assertDatabaseMissing('email_change_otps', ['user_id' => $user->id]);
    }

    /**
     * Test verifikasi OTP salah: menambah hitungan percobaan dan gagal.
     */
    public function test_verify_otp_fails_on_wrong_otp_and_increments_attempts(): void
    {
        $user = User::factory()->create([
            'role' => 'admin_seller',
        ]);

        $otpRecord = EmailChangeOtp::create([
            'user_id' => $user->id,
            'new_email' => 'wrong@example.com',
            'otp_hash' => Hash::make('123456'),
            'attempts' => 0,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        $response = $this->actingAs($user)->post(route('admin.account.email.verify-otp'), [
            'otp' => '654321',
        ]);

        $response->assertSessionHasErrors('otp');

        $otpRecord->refresh();
        $this->assertSame(1, $otpRecord->attempts);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => 'email_change_otp_failed',
        ]);
    }

    /**
     * Test verifikasi OTP dihapus dan diblokir setelah 3x percobaan gagal.
     */
    public function test_verify_otp_deletes_record_after_exceeding_attempts(): void
    {
        $user = User::factory()->create([
            'role' => 'admin_seller',
        ]);

        EmailChangeOtp::create([
            'user_id' => $user->id,
            'new_email' => 'exceeded@example.com',
            'otp_hash' => Hash::make('123456'),
            'attempts' => 3,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        $response = $this->actingAs($user)->post(route('admin.account.email.verify-otp'), [
            'otp' => '123456',
        ]);

        $response->assertSessionHasErrors('otp');
        $this->assertDatabaseMissing('email_change_otps', ['user_id' => $user->id]);
    }

    /**
     * Test verifikasi OTP berhasil: pending email change dibuat, email konfirmasi dikirim ke email baru, cooldown aktif.
     */
    public function test_verify_otp_success_creates_pending_and_sends_verification_mail(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'name' => 'Siti Sukses',
            'email' => 'siti.current@example.com',
            'role' => 'admin_seller',
        ]);

        $newEmail = 'siti.new@example.com';
        $correctOtp = '852963';

        EmailChangeOtp::create([
            'user_id' => $user->id,
            'new_email' => $newEmail,
            'otp_hash' => Hash::make($correctOtp),
            'attempts' => 0,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        $response = $this->actingAs($user)
            ->withSession(['otp_target_email' => $newEmail])
            ->post(route('admin.account.email.verify-otp'), [
                'otp' => $correctOtp,
            ]);

        $response->assertRedirect(route('admin.account'));
        $response->assertSessionHas('success');
        $response->assertSessionMissing('otp_target_email');

        // OTP record harus terhapus
        $this->assertDatabaseMissing('email_change_otps', ['user_id' => $user->id]);

        // PendingEmailChange harus dibuat
        $pending = PendingEmailChange::where('user_id', $user->id)->first();
        $this->assertNotNull($pending);
        $this->assertSame($newEmail, $pending->new_email);
        $this->assertSame(64, strlen($pending->token));

        // Cooldown harus terupdate
        $user->refresh();
        $this->assertNotNull($user->last_email_change_requested_at);

        // Email konfirmasi harus dikirim ke EMAIL BARU
        Mail::assertSent(EmailChangeVerificationMail::class, function ($mail) use ($newEmail, $user) {
            return $mail->hasTo($newEmail)
                && $mail->userName === $user->name;
        });

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => 'email_change_otp_verified',
        ]);
    }

    /**
     * Test tampilan Step 1 (form input email baru) dan Step 2 (form input OTP).
     */
    public function test_account_view_displays_step1_and_step2_correctly(): void
    {
        $user = User::factory()->create([
            'email' => 'testview@example.com',
            'role' => 'admin_seller',
        ]);

        // Kondisi default: Step 1 (input email baru)
        $response1 = $this->actingAs($user)->get(route('admin.account'));
        $response1->assertOk();
        $response1->assertSee('Cara Kerja Verifikasi');
        $response1->assertSee('Kirim Kode OTP ke Email Saya');
        $response1->assertDontSee('Kode OTP (6 Digit)');

        // Kondisi Step 2: ketika ada session otp_sent / otp_target_email
        $response2 = $this->actingAs($user)
            ->withSession([
                'otp_sent' => 'Kode OTP telah dikirim',
                'otp_target_email' => 'newtarget@example.com',
            ])
            ->get(route('admin.account'));

        $response2->assertOk();
        $response2->assertSee('Kode OTP Telah Dikirim!');
        $response2->assertSee('Kode OTP (6 Digit)');
        $response2->assertSee('Verifikasi OTP & Kirim Konfirmasi Email', false);
        $response2->assertSee('newtarget@example.com');
        $response2->assertSee('Tidak menerima kode? Kembali dan minta ulang');
    }

    /**
     * Test alur lengkap end-to-end ganti email:
     * Request OTP -> Terima OTP di email aktif -> Verifikasi OTP -> Terima link verifikasi di email baru -> Klik link -> Email berganti & Google terputus.
     */
    public function test_full_end_to_end_email_change_flow(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'name' => 'Ahmad Pengguna',
            'email' => 'ahmad.lama@linkan.id',
            'google_id' => 'ahmad-google-id',
            'role' => 'admin_seller',
        ]);

        $newEmail = 'ahmad.baru@linkan.id';

        // 1. User minta OTP untuk ganti ke $newEmail
        $reqResponse = $this->actingAs($user)->post(route('admin.account.email.request-otp'), [
            'new_email' => $newEmail,
        ]);
        $reqResponse->assertRedirect(route('admin.account'));
        $reqResponse->assertSessionHas('otp_sent');

        // Pastikan OTP dikirim ke ahmad.lama@linkan.id
        $sentOtp = null;
        Mail::assertSent(EmailChangeOtpMail::class, function ($mail) use ($user, &$sentOtp) {
            if ($mail->hasTo($user->email)) {
                $sentOtp = $mail->otp;

                return true;
            }

            return false;
        });
        $this->assertNotNull($sentOtp);

        // 2. User memasukkan OTP yang diterima
        $verifyResponse = $this->actingAs($user)->post(route('admin.account.email.verify-otp'), [
            'otp' => $sentOtp,
        ]);
        $verifyResponse->assertRedirect(route('admin.account'));
        $verifyResponse->assertSessionHas('success');

        // Pastikan PendingEmailChange dibuat dan email konfirmasi dikirim ke ahmad.baru@linkan.id
        $pending = PendingEmailChange::where('user_id', $user->id)->first();
        $this->assertNotNull($pending);
        $this->assertSame($newEmail, $pending->new_email);

        Mail::assertSent(EmailChangeVerificationMail::class, function ($mail) use ($newEmail) {
            return $mail->hasTo($newEmail);
        });

        // 3. User mengklik URL verifikasi dari email baru
        $confirmResponse = $this->actingAs($user)->get(route('admin.account.email.verify', ['token' => $pending->token]));
        $confirmResponse->assertRedirect(route('admin.account'));
        $confirmResponse->assertSessionHas('success');

        // 4. Verifikasi hasil akhir: email terupdate, google_id terputus, pending change bersih
        $user->refresh();
        $this->assertSame($newEmail, $user->email);
        $this->assertNull($user->google_id);
        $this->assertDatabaseMissing('pending_email_changes', ['user_id' => $user->id]);
    }
}
