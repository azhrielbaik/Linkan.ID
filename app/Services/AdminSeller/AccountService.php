<?php

namespace App\Services\AdminSeller;

use App\Mail\EmailChangeOtpMail;
use App\Mail\EmailChangeVerificationMail;
use App\Models\Appearance;
use App\Models\DigitalProduct;
use App\Models\EmailChangeOtp;
use App\Models\PendingEmailChange;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AccountService
{
    /**
     * Update account details for a user.
     */
    public function updateAccount(User $user, array $data, ?UploadedFile $avatar = null, ?string $password = null): void
    {
        DB::transaction(function () use ($user, $data, $avatar, $password) {
            $user->username = $data['username'];
            $user->name = $data['name'];
            if (isset($data['bio'])) {
                $user->bio = $data['bio'];
            }

            if (isset($data['remove_avatar']) && $data['remove_avatar']) {
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $user->avatar = null;
            } elseif ($avatar) {
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $path = $avatar->store('avatars', 'public');
                $user->avatar = $path;
            }

            if ($password) {
                $user->password = Hash::make($password);
            }

            $user->save();

            ActivityLogger::log(
                'update_account',
                "User {$user->name} memperbarui informasi akun" . ($password ? " dan kata sandi" : "") . ".",
                ['username' => $user->username, 'password_changed' => (bool)$password],
                $user->id
            );
        });
    }

    /**
     * Change user password securely.
     */
    public function changePassword(User $user, string $newPassword): void
    {
        DB::transaction(function () use ($user, $newPassword) {
            $user->password = Hash::make($newPassword);
            $user->save();

            ActivityLogger::log(
                'change_password',
                "User {$user->name} berhasil mengubah kata sandi akun.",
                ['user_id' => $user->id],
                $user->id
            );
        });
    }

    /**
     * Update notification preferences for a user.
     */
    public function updateNotificationPreferences(User $user, array $preferences): void
    {
        DB::transaction(function () use ($user, $preferences) {
            $user->notification_preferences = $preferences;
            $user->save();

            ActivityLogger::log(
                'update_notification_preferences',
                "User {$user->name} memperbarui preferensi notifikasi email.",
                ['preferences' => $preferences],
                $user->id
            );
        });
    }

    /**
     * Get email change cooldown status and remaining seconds.
     *
     * @param User $user
     * @return array [bool $isCooldown, int $secondsRemaining]
     */
    public function getEmailChangeCooldown(User $user): array
    {
        $rateLimitKey = 'email_change|'.$user->id;
        $hasDbCooldown = $user->last_email_change_requested_at && $user->last_email_change_requested_at->copy()->addDay()->isFuture();
        $isCooldown = RateLimiter::tooManyAttempts($rateLimitKey, 1) || $hasDbCooldown;
        $seconds = 0;

        if ($isCooldown) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            if ($seconds <= 0 && $hasDbCooldown) {
                $seconds = max(0, (int) now()->diffInSeconds($user->last_email_change_requested_at->copy()->addDay(), false));
            }
        }

        return [$isCooldown, $seconds];
    }

    /**
     * Request OTP for email change with cooldown and rate-limiting.
     *
     * @throws ValidationException
     */
    public function requestEmailChangeOtp(User $user, string $newEmail): void
    {
        $rateLimitKey = 'email_change|'.$user->id;
        $hasDbCooldown = $user->last_email_change_requested_at
            && $user->last_email_change_requested_at->copy()->addDay()->isFuture();

        if (RateLimiter::tooManyAttempts($rateLimitKey, 1) || $hasDbCooldown) {
            $availableIn = RateLimiter::availableIn($rateLimitKey);
            $availableHours = max(1, ceil($availableIn / 3600));

            throw ValidationException::withMessages([
                'new_email' => "Anda sudah melakukan permintaan ganti email dalam 24 jam terakhir. Coba lagi dalam {$availableHours} jam.",
            ]);
        }

        $otpRequestKey = 'otp_request|'.$user->id;
        if (RateLimiter::tooManyAttempts($otpRequestKey, 3)) {
            $seconds = RateLimiter::availableIn($otpRequestKey);

            throw ValidationException::withMessages([
                'new_email' => "Terlalu banyak permintaan OTP. Coba lagi dalam {$seconds} detik.",
            ]);
        }
        RateLimiter::hit($otpRequestKey, 900);

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $otpHash = Hash::make($otp);

        EmailChangeOtp::where('user_id', $user->id)->delete();
        EmailChangeOtp::create([
            'user_id' => $user->id,
            'new_email' => $newEmail,
            'otp_hash' => $otpHash,
            'attempts' => 0,
            'expires_at' => now()->addMinutes(10),
        ]);

        try {
            Mail::to($user->email)->send(new EmailChangeOtpMail($otp, $user->name, $newEmail));
        } catch (\Exception $e) {
            Log::error('Gagal mengirim OTP ganti email: '.$e->getMessage(), ['user_id' => $user->id]);
            EmailChangeOtp::where('user_id', $user->id)->delete();

            throw ValidationException::withMessages([
                'new_email' => 'Gagal mengirim kode OTP. Silakan coba beberapa saat lagi.',
            ]);
        }

        ActivityLogger::log(
            'email_change_otp_requested',
            "User {$user->name} meminta OTP untuk ganti email ke {$newEmail}.",
            ['new_email' => $newEmail],
            $user->id
        );
    }

    /**
     * Verify OTP and initiate pending email confirmation.
     *
     * @return string The verified new email address
     * @throws ValidationException
     */
    public function verifyEmailChangeOtp(User $user, string $otp): string
    {
        $otpRecord = EmailChangeOtp::where('user_id', $user->id)->first();

        if (! $otpRecord) {
            throw ValidationException::withMessages([
                'otp' => 'Tidak ada permintaan OTP aktif. Silakan mulai dari awal.',
            ]);
        }

        if ($otpRecord->isExpired()) {
            $otpRecord->delete();

            throw ValidationException::withMessages([
                'otp' => 'Kode OTP sudah kedaluwarsa (lebih dari 10 menit). Silakan minta kode baru.',
            ]);
        }

        if ($otpRecord->isExceededAttempts()) {
            $otpRecord->delete();

            throw ValidationException::withMessages([
                'otp' => 'Kode OTP tidak valid terlalu banyak kali. Silakan minta kode baru.',
            ]);
        }

        if (! Hash::check($otp, $otpRecord->otp_hash)) {
            $otpRecord->increment('attempts');
            $remaining = 3 - $otpRecord->fresh()->attempts;

            ActivityLogger::log(
                'email_change_otp_failed',
                "User {$user->name} memasukkan OTP yang salah untuk ganti email.",
                ['attempts_remaining' => $remaining],
                $user->id
            );

            throw ValidationException::withMessages([
                'otp' => "Kode OTP salah. Sisa percobaan: {$remaining} kali.",
            ]);
        }

        $newEmail = $otpRecord->new_email;

        if (User::where('email', $newEmail)->where('id', '!=', $user->id)->exists()) {
            $otpRecord->delete();

            throw ValidationException::withMessages([
                'otp' => 'Email tujuan sudah digunakan oleh akun lain. Silakan mulai dari awal dengan email baru.',
            ]);
        }

        $otpRecord->delete();

        PendingEmailChange::where('user_id', $user->id)->delete();

        $token = Str::random(64);
        PendingEmailChange::create([
            'user_id' => $user->id,
            'new_email' => $newEmail,
            'token' => $token,
            'expires_at' => now()->addHours(24),
        ]);

        $verificationUrl = route('admin.account.email.verify', $token);

        try {
            Mail::to($newEmail)->send(new EmailChangeVerificationMail($verificationUrl, $user->name));
        } catch (\Exception $e) {
            Log::error('Gagal kirim email verifikasi setelah OTP valid: '.$e->getMessage());
            PendingEmailChange::where('token', $token)->delete();

            throw ValidationException::withMessages([
                'otp' => 'OTP valid, namun gagal mengirim email konfirmasi ke alamat baru. Silakan coba lagi.',
            ]);
        }

        $user->last_email_change_requested_at = now();
        $user->save();

        RateLimiter::hit('email_change|'.$user->id, 86400);

        ActivityLogger::log(
            'email_change_otp_verified',
            "User {$user->name} berhasil verifikasi OTP dan email konfirmasi dikirim ke {$newEmail}.",
            ['new_email' => $newEmail],
            $user->id
        );

        return $newEmail;
    }

    /**
     * Finalize email change through verification token.
     *
     * @return array
     * @throws ValidationException
     */
    public function verifyEmailChange(string $token): array
    {
        $pending = PendingEmailChange::where('token', $token)->first();

        if (! $pending) {
            throw ValidationException::withMessages([
                'email' => 'Tautan verifikasi email tidak valid atau sudah pernah digunakan.',
            ]);
        }

        if ($pending->isExpired()) {
            $pending->delete();

            throw ValidationException::withMessages([
                'email' => 'Tautan verifikasi email telah kadaluwarsa (lebih dari 24 jam). Silakan ajukan permintaan baru.',
            ]);
        }

        if (User::where('email', $pending->new_email)->where('id', '!=', $pending->user_id)->exists()) {
            $pending->delete();

            throw ValidationException::withMessages([
                'email' => 'Alamat email baru tersebut sudah digunakan oleh akun lain.',
            ]);
        }

        $user = User::findOrFail($pending->user_id);
        $oldEmail = $user->email;
        $newEmail = $pending->new_email;
        $wasGoogleConnected = ! empty($user->google_id);

        $user->email = $newEmail;
        $user->email_verified_at = now();
        $user->google_id = null;
        $user->save();

        $pending->delete();

        ActivityLogger::log(
            'email_changed',
            "User {$user->name} berhasil mengubah alamat email dari {$oldEmail} ke {$newEmail}.",
            ['old_email' => $oldEmail, 'new_email' => $newEmail],
            $user->id
        );

        if ($wasGoogleConnected) {
            ActivityLogger::log(
                'google_disconnected_on_email_change',
                "Koneksi Google user {$user->name} diputuskan otomatis karena perubahan email akun.",
                [
                    'old_email' => $oldEmail,
                    'new_email' => $newEmail,
                ],
                $user->id
            );
        }

        $message = $wasGoogleConnected
            ? 'Alamat email akun Anda berhasil diperbarui menjadi '.$newEmail.'. Koneksi Google Anda telah diputuskan secara otomatis karena email akun berubah. Silakan hubungkan kembali di halaman Layanan Terhubung jika diperlukan.'
            : 'Alamat email akun Anda berhasil diperbarui menjadi '.$newEmail.'.';

        return [
            'success' => true,
            'message' => $message,
            'user' => $user,
        ];
    }

    /**
     * Disconnect Google account.
     *
     * @throws ValidationException
     */
    public function disconnectGoogle(User $user): void
    {
        if (empty($user->password)) {
            throw ValidationException::withMessages([
                'google' => 'Anda harus mengatur password akun terlebih dahulu sebelum memutus koneksi Google.',
            ]);
        }

        $user->google_id = null;
        $user->save();

        ActivityLogger::log(
            'google_disconnected',
            "User {$user->name} memutuskan koneksi login akun Google.",
            [],
            $user->id
        );
    }

    /**
     * Soft delete user account.
     *
     * Sebelum soft-delete, email/username/google_id di-anonymize agar
     * unique constraint tidak menghalangi user mendaftar ulang dengan
     * email yang sama di kemudian hari.
     */
    public function deleteAccount(User $user): void
    {
        DB::transaction(function () use ($user) {
            Appearance::where('user_id', $user->id)->delete();
            DigitalProduct::where('user_id', $user->id)->delete();

            // Anonymize kolom unik agar tidak memblokir pendaftaran ulang
            // dengan email / username yang sama setelah akun ini dihapus.
            $anonymousSuffix = '_deleted_' . $user->id . '_' . now()->timestamp;
            $user->email     = $user->email . $anonymousSuffix;
            $user->username  = $user->username . $anonymousSuffix;
            $user->google_id = null;
            $user->save();

            ActivityLogger::log(
                'account_deleted',
                "Akun user ID {$user->id} telah dihapus (soft-delete) dan data uniknya dianonimkan.",
                ['user_id' => $user->id],
                $user->id
            );

            $user->delete();
        });
    }
}
