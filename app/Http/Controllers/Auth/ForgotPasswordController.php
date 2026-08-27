<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordOtpMail;
use App\Models\PasswordResetRequest;
use App\Models\ActivityLog;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Step 1: Halaman Formulir Masukkan Email.
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Step 1 -> Step 2: Kirim kode OTP reset password langsung ke email pengguna (SMTP).
     */
    public function requestOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ], [
            'email.required' => 'Email address is required.',
            'email.email'    => 'Please enter a valid email address.',
        ]);

        $email = strtolower(trim($request->email));
        $requestKey = 'password-reset-request|' . $request->ip() . '|' . hash('sha256', $email);
        if (RateLimiter::tooManyAttempts($requestKey, 3)) {
            return back()->withInput()->with('status', 'Jika email terdaftar, instruksi reset password akan dikirim.');
        }
        RateLimiter::hit($requestKey, 900);

        $user = User::whereRaw('LOWER(email) = ?', [$email])->first();

        if (!$user) {
            return back()->withInput()->with('status', 'Jika email terdaftar, instruksi reset password akan dikirim.');
        }

        // Generate 4-digit OTP acak
        $otp = str_pad((string) random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
        $resetToken = Str::random(64);
        $expiresMinutes = 1;

        // Tandai permohonan aktif sebelumnya agar tidak duplikat
        PasswordResetRequest::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->update([
                'status'      => 'rejected',
                'admin_notes' => 'Digantikan oleh permintaan kode OTP baru',
                'resolved_at' => now(),
            ]);

        // Simpan request OTP baru dengan status langsung approved
        $resetRequest = PasswordResetRequest::create([
            'user_id'     => $user->id,
            'email'       => $user->email,
            'reset_token_hash' => hash('sha256', $resetToken),
            'reason'      => 'Permintaan reset password via email OTP',
            'otp_hash'    => Hash::make($otp),
            'status'      => 'approved',
            'expires_at'  => now()->addMinutes($expiresMinutes),
            'resolved_at' => now(),
        ]);

        // Kirim email OTP langsung via SMTP
        try {
            Mail::to($user->email)->send(new ResetPasswordOtpMail($otp, $user->name ?? 'Pengguna', $expiresMinutes));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim email OTP Reset Password: ' . $e->getMessage());
            return back()->withInput()->with('status', 'Jika email terdaftar, instruksi reset password akan dikirim.');
        }

        // Simpan email di session
        session([
            'reset_token' => $resetToken,
            'reset_request_id' => $resetRequest->id,
        ]);

        // Catat di activity log
        ActivityLogger::log(
            'password_reset_otp_sent',
            "Kode OTP verifikasi reset password dikirimkan ke {$user->email}.",
            ['user_id' => $user->id, 'email' => $user->email]
        );

        return redirect()->route('password.verify-otp', ['token' => $resetToken])
            ->with('status', 'Kode OTP 4 digit telah dikirim ke email Anda. Silakan periksa inbox atau folder spam.');
    }

    /**
     * Step 2: Halaman Verifikasi Kode OTP (4 Digit).
     */
    public function showVerifyOtpForm(Request $request)
    {
        $token = $request->query('token') ?? session('reset_token');
        if (!$token) {
            return redirect()->route('password.request');
        }

        $latestRequest = PasswordResetRequest::where('reset_token_hash', hash('sha256', $token))
            ->where('status', 'approved')
            ->first();
        if (!$latestRequest || $latestRequest->used_at) {
            return redirect()->route('password.request')->with('status', 'Tautan reset password tidak valid atau sudah kedaluwarsa.');
        }

        return view('auth.verify-otp', [
            'email'         => ActivityLog::maskEmail($latestRequest->email),
            'token'         => $token,
            'latestRequest' => $latestRequest,
        ]);
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'token' => 'required|string|size:64',
        ]);

        $tokenHash = hash('sha256', $request->token);
        $resendKey = 'password-reset-resend|' . $request->ip() . '|' . $tokenHash;
        if (RateLimiter::tooManyAttempts($resendKey, 1)) {
            return redirect()->route('password.verify-otp', ['token' => $request->token])
                ->with('status', 'Silakan tunggu sebentar sebelum meminta kode baru.');
        }

        $resetRequest = PasswordResetRequest::with('user')
            ->where('reset_token_hash', $tokenHash)
            ->where('status', 'approved')
            ->first();

        if (!$resetRequest || $resetRequest->used_at || !$resetRequest->user) {
            return redirect()->route('password.request')
                ->with('status', 'Tautan reset password tidak valid atau sudah kedaluwarsa.');
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
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim ulang email OTP Reset Password: ' . $e->getMessage());
            $newRequest->update(['status' => 'rejected', 'resolved_at' => now()]);
            return redirect()->route('password.verify-otp', ['token' => $request->token])
                ->with('status', 'Jika kode belum diterima, silakan coba lagi setelah beberapa saat.');
        }

        session([
            'reset_token' => $newToken,
            'reset_request_id' => $newRequest->id,
        ]);

        return redirect()->route('password.verify-otp', ['token' => $newToken])
            ->with('status', 'Kode OTP baru telah dikirim ke email Anda.');
    }

    /**
     * API Status Check untuk Auto-Fill OTP saat Admin Setujui.
     */
    public function checkOtpStatus(Request $request)
    {
        $token = $request->query('token');
        if (!$token) {
            return response()->json(['status' => 'error'], 400);
        }

        $req = PasswordResetRequest::where('reset_token_hash', hash('sha256', $token))->first();

        if (!$req) {
            return response()->json(['status' => 'none']);
        }

        if ($req->isExpired() || $req->used_at) {
            return response()->json(['status' => 'expired']);
        }

        return response()->json(['status' => $req->status]);
    }

    /**
     * Step 2 -> Step 3: Verifikasi Kode OTP.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'token'    => 'required|string|size:64',
            'otp_code' => 'required|string|size:4',
        ], [
            'otp_code.required' => 'Please enter the 4-digit code.',
            'otp_code.size'     => 'The verification code must be 4 digits.',
        ]);

        $resetReq = PasswordResetRequest::where('reset_token_hash', hash('sha256', $request->token))
            ->where('status', 'approved')
            ->first();

        if (!$resetReq) {
            return back()->withInput()->withErrors([
                'otp_code' => 'Tidak ada kode OTP aktif yang ditemukan. Silakan minta kode baru.',
            ]);
        }

        if ($resetReq->isExpired() || $resetReq->used_at) {
            return back()->withInput()->withErrors([
                'otp_code' => 'Kode verifikasi telah kedaluwarsa. Silakan minta kode baru.',
            ]);
        }

        $otpKey = 'password-reset-otp|' . $request->ip() . '|' . $resetReq->id;
        if ($resetReq->attempts >= 5 || RateLimiter::tooManyAttempts($otpKey, 5)) {
            return back()->withErrors([
                'otp_code' => 'Terlalu banyak percobaan OTP. Silakan minta kode baru.',
            ]);
        }

        $resetReq->increment('attempts');
        RateLimiter::hit($otpKey, 900);

        if (!Hash::check(trim($request->otp_code), $resetReq->otp_hash)) {
            return back()->withInput()->withErrors([
                'otp_code' => 'Kode OTP salah. Silakan periksa kembali.',
            ]);
        }

        // Simpan sesi verifikasi OTP untuk step 3
        RateLimiter::clear($otpKey);
        session(['otp_request_id' => $resetReq->id]);

        return redirect()->route('password.create-new');
    }

    /**
     * Step 3: Halaman Buat Password Baru.
     */
    public function showCreatePasswordForm(Request $request)
    {
        $resetRequest = PasswordResetRequest::with('user')->find(session('otp_request_id'));
        if (!$resetRequest || $resetRequest->status !== 'approved' || $resetRequest->isExpired() || $resetRequest->used_at) {
            return redirect()->route('password.request');
        }

        return view('auth.create-new-password', [
            'email' => ActivityLog::maskEmail($resetRequest->email),
        ]);
    }

    /**
     * Step 3 -> Step 4: Simpan Password Baru.
     */
    public function submitCreatePassword(Request $request)
    {
        $resetRequest = PasswordResetRequest::with('user')->find(session('otp_request_id'));
        if (!$resetRequest || $resetRequest->status !== 'approved' || $resetRequest->isExpired() || $resetRequest->used_at) {
            return redirect()->route('password.request')->withErrors(['email' => 'Sesi reset password tidak valid atau sudah kedaluwarsa.']);
        }

        $request->validate([
            'password'              => 'required|string|min:8|same:password_confirmation',
            'password_confirmation' => 'required|string|min:8',
        ], [
            'password.required' => 'Please enter a new password.',
            'password.min'      => 'Password must be at least 8 characters.',
            'password.same'     => 'Password confirmation does not match.',
        ]);

        $user = $resetRequest->user;
        if (!$user) {
            return redirect()->route('password.request')->withErrors(['email' => 'User not found.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Tandai permohonan selesai
        $resetRequest->update([
            'status' => 'completed',
            'resolved_at' => now(),
            'used_at' => now(),
        ]);

        // Catat di log
        ActivityLogger::log(
            'password_reset_success',
            "User {$user->name} ({$user->email}) berhasil memperbarui password baru.",
            ['user_id' => $user->id, 'email' => $user->email]
        );

        // Hapus session temporary
        session()->forget(['reset_token', 'reset_request_id', 'otp_request_id']);

        return redirect()->route('password.success');
    }

    /**
     * Step 4: Halaman Sukses Password Berhasil Diubah.
     */
    public function showSuccessPage()
    {
        return view('auth.password-reset-success');
    }
}
