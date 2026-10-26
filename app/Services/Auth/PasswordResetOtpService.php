<?php

namespace App\Services\Auth;

use App\Mail\ResetPasswordOtpMail;
use App\Models\ActivityLog;
use App\Models\PasswordResetRequest;
use App\Models\User;
use App\Services\ActivityLogger;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class PasswordResetOtpService
{
    public function requestOtp(string $email, string $ip): array
    {
        $normalizedEmail = strtolower(trim($email));
        $requestKey = 'password-reset-request|'.$ip.'|'.hash('sha256', $normalizedEmail);

        if (RateLimiter::tooManyAttempts($requestKey, 3)) {
            return [
                'success' => true,
                'message' => 'Jika email terdaftar, instruksi reset password akan dikirim.',
            ];
        }
        RateLimiter::hit($requestKey, 900);

        $user = User::whereRaw('LOWER(email) = ?', [$normalizedEmail])->first();

        if (! $user) {
            return [
                'success' => true,
                'message' => 'Jika email terdaftar, instruksi reset password akan dikirim.',
            ];
        }

        $otp = str_pad((string) random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
        $resetToken = Str::random(64);
        $expiresMinutes = 1;

        $resetRequest = DB::transaction(function () use ($user, $resetToken, $otp, $expiresMinutes) {
            PasswordResetRequest::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'approved'])
                ->update([
                    'status' => 'rejected',
                    'admin_notes' => 'Digantikan oleh permintaan kode OTP baru',
                    'resolved_at' => now(),
                ]);

            $resetRequest = PasswordResetRequest::create([
                'user_id' => $user->id,
                'email' => $user->email,
                'reset_token_hash' => hash('sha256', $resetToken),
                'reason' => 'Permintaan reset password via email OTP',
                'otp_hash' => Hash::make($otp),
                'status' => 'approved',
                'expires_at' => now()->addMinutes($expiresMinutes),
                'resolved_at' => now(),
            ]);

            ActivityLogger::log(
                'password_reset_otp_sent',
                "Kode OTP verifikasi reset password dikirimkan ke {$user->email}.",
                ['user_id' => $user->id, 'email' => $user->email]
            );

            return $resetRequest;
        });

        try {
            Mail::to($user->email)->send(new ResetPasswordOtpMail($otp, $user->name ?? 'Pengguna', $expiresMinutes));
        } catch (Exception $e) {
            Log::error('Gagal mengirim email OTP Reset Password: '.$e->getMessage());

            return [
                'success' => true,
                'message' => 'Jika email terdaftar, instruksi reset password akan dikirim.',
            ];
        }

        return [
            'success' => true,
            'token' => $resetToken,
            'reset_request_id' => $resetRequest->id,
            'message' => 'Kode OTP 4 digit telah dikirim ke email Anda. Silakan periksa inbox atau folder spam.',
        ];
    }

    public function getVerifyData(?string $token): ?array
    {
        if (! $token) {
            return null;
        }

        $latestRequest = PasswordResetRequest::where('reset_token_hash', hash('sha256', $token))
            ->where('status', 'approved')
            ->first();

        if (! $latestRequest || $latestRequest->used_at) {
            return null;
        }

        return [
            'email' => ActivityLog::maskEmail($latestRequest->email),
            'token' => $token,
            'latestRequest' => $latestRequest,
        ];
    }

    public function resendOtp(string $token, string $ip): array
    {
        $tokenHash = hash('sha256', $token);
        $resendKey = 'password-reset-resend|'.$ip.'|'.$tokenHash;

        if (RateLimiter::tooManyAttempts($resendKey, 1)) {
            return [
                'success' => false,
                'message' => 'Silakan tunggu sebentar sebelum meminta kode baru.',
                'token' => $token,
            ];
        }

        $resetRequest = PasswordResetRequest::with('user')
            ->where('reset_token_hash', $tokenHash)
            ->where('status', 'approved')
            ->first();

        if (! $resetRequest || $resetRequest->used_at || ! $resetRequest->user) {
            return [
                'success' => false,
                'invalid' => true,
                'message' => 'Tautan reset password tidak valid atau sudah kedaluwarsa.',
            ];
        }

        RateLimiter::hit($resendKey, 60);
        $otp = str_pad((string) random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
        $newToken = Str::random(64);

        $resetRequest->update([
            'status' => 'rejected',
            'resolved_at' => now(),
        ]);

        $newRequest = PasswordResetRequest::create([
            'user_id' => $resetRequest->user_id,
            'email' => $resetRequest->email,
            'reset_token_hash' => hash('sha256', $newToken),
            'reason' => 'Permintaan ulang kode OTP reset password',
            'otp_hash' => Hash::make($otp),
            'status' => 'approved',
            'expires_at' => now()->addMinute(),
            'resolved_at' => now(),
        ]);

        try {
            Mail::to($resetRequest->email)->send(new ResetPasswordOtpMail(
                $otp,
                $resetRequest->user->name ?? 'Pengguna',
                1
            ));
        } catch (Exception $e) {
            Log::error('Gagal mengirim ulang email OTP Reset Password: '.$e->getMessage());
            $newRequest->update(['status' => 'rejected', 'resolved_at' => now()]);

            return [
                'success' => false,
                'message' => 'Jika kode belum diterima, silakan coba lagi setelah beberapa saat.',
                'token' => $token,
            ];
        }

        return [
            'success' => true,
            'token' => $newToken,
            'reset_request_id' => $newRequest->id,
            'message' => 'Kode OTP baru telah dikirim ke email Anda.',
        ];
    }

    public function checkOtpStatus(?string $token): string
    {
        if (! $token) {
            return 'error';
        }

        $req = PasswordResetRequest::where('reset_token_hash', hash('sha256', $token))->first();

        if (! $req) {
            return 'none';
        }

        if ($req->isExpired() || $req->used_at) {
            return 'expired';
        }

        return (string) $req->status;
    }

    public function verifyOtp(string $token, string $otpCode, string $ip): array
    {
        $resetReq = PasswordResetRequest::where('reset_token_hash', hash('sha256', $token))
            ->where('status', 'approved')
            ->first();

        if (! $resetReq) {
            return [
                'success' => false,
                'message' => 'Tidak ada kode OTP aktif yang ditemukan. Silakan minta kode baru.',
            ];
        }

        if ($resetReq->isExpired() || $resetReq->used_at) {
            return [
                'success' => false,
                'message' => 'Kode verifikasi telah kedaluwarsa. Silakan minta kode baru.',
            ];
        }

        $otpKey = 'password-reset-otp|'.$ip.'|'.$resetReq->id;
        if ($resetReq->attempts >= 5 || RateLimiter::tooManyAttempts($otpKey, 5)) {
            return [
                'success' => false,
                'message' => 'Terlalu banyak percobaan OTP. Silakan minta kode baru.',
            ];
        }

        $resetReq->increment('attempts');
        RateLimiter::hit($otpKey, 900);

        if (! Hash::check(trim($otpCode), $resetReq->otp_hash)) {
            return [
                'success' => false,
                'message' => 'Kode OTP salah. Silakan periksa kembali.',
            ];
        }

        RateLimiter::clear($otpKey);

        return [
            'success' => true,
            'reset_request_id' => $resetReq->id,
        ];
    }

    public function resetPassword(int $requestId, string $newPassword): array
    {
        $resetRequest = PasswordResetRequest::with('user')->find($requestId);
        if (! $resetRequest || $resetRequest->status !== 'approved' || $resetRequest->isExpired() || $resetRequest->used_at) {
            return [
                'success' => false,
                'message' => 'Sesi reset password tidak valid atau sudah kedaluwarsa.',
            ];
        }

        $user = $resetRequest->user;
        if (! $user) {
            return [
                'success' => false,
                'message' => 'User not found.',
            ];
        }

        DB::transaction(function () use ($user, $newPassword, $resetRequest) {
            $user->update([
                'password' => Hash::make($newPassword),
            ]);

            $resetRequest->update([
                'status' => 'completed',
                'resolved_at' => now(),
                'used_at' => now(),
            ]);

            ActivityLogger::log(
                'password_reset_success',
                "User {$user->name} ({$user->email}) berhasil memperbarui password baru.",
                ['user_id' => $user->id, 'email' => $user->email]
            );
        });

        return ['success' => true];
    }
}
