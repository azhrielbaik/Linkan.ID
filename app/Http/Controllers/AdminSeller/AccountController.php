<?php

namespace App\Http\Controllers\AdminSeller;

use App\Http\Controllers\Controller;
use App\Mail\EmailChangeOtpMail;
use App\Mail\EmailChangeVerificationMail;
use App\Models\EmailChangeOtp;
use App\Models\PendingEmailChange;
use App\Models\User;
use App\Services\ActivityLogger;
use App\Services\AdminSeller\AccountService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    protected $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function edit()
    {
        $user = Auth::user();
        $notifPrefs = $user->notification_preferences ?? [
            'new_sales' => true,
            'payout' => true,
            'security_alerts' => true,
            'newsletter' => false,
        ];

        // Active sessions for the authenticated user
        $currentSessionId = session()->getId();
        $rawSessions = DB::table('sessions')
            ->where('user_id', $user->id)
            ->orderByDesc('last_activity')
            ->get();

        $sessions = $rawSessions->map(function ($session) use ($currentSessionId) {
            $userAgent = $session->user_agent ?? '';

            return (object) [
                'id' => $session->id,
                'ip_address' => $session->ip_address,
                'user_agent' => $userAgent,
                'browser' => $this->parseBrowser($userAgent),
                'device' => $this->parseDevice($userAgent),
                'last_activity' => $session->last_activity,
                'last_active_human' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                'is_current' => ($session->id === $currentSessionId),
            ];
        });

        // 5 most recent login logs from activity_logs table
        $loginHistory = DB::table('activity_logs')
            ->where('user_id', $user->id)
            ->where('action', 'user_login')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $rateLimitKey = 'email_change|'.$user->id;
        $hasDbCooldown = $user->last_email_change_requested_at && $user->last_email_change_requested_at->copy()->addDay()->isFuture();
        $emailChangeCooldown = RateLimiter::tooManyAttempts($rateLimitKey, 1) || $hasDbCooldown;
        $emailChangeCooldownSeconds = 0;

        if ($emailChangeCooldown) {
            $emailChangeCooldownSeconds = RateLimiter::availableIn($rateLimitKey);
            if ($emailChangeCooldownSeconds <= 0 && $hasDbCooldown) {
                $emailChangeCooldownSeconds = max(0, (int) now()->diffInSeconds($user->last_email_change_requested_at->copy()->addDay(), false));
            }
        }

        return view('admin_seller.features.account.index', compact(
            'user', 'notifPrefs', 'sessions', 'loginHistory',
            'emailChangeCooldown', 'emailChangeCooldownSeconds'
        ));
    }

    public function update(Request $request)
    {
        $rules = [
            'username' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_avatar' => 'nullable|boolean',
        ];

        if ($request->filled('password')) {
            $rules['current_password'] = 'required|string';
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules, [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        if ($request->filled('password')) {
            if (! Hash::check($request->current_password, Auth::user()->password)) {
                return back()->withErrors([
                    'current_password' => 'Password saat ini tidak sesuai.',
                ])->withInput();
            }
        }

        $this->accountService->updateAccount(
            Auth::user(),
            $request->only(['username', 'name', 'bio', 'remove_avatar']),
            $request->file('avatar'),
            $request->input('password')
        );

        if ($request->filled('password')) {
            return redirect()->route('admin.account')->with('success', 'Password berhasil diperbarui.');
        }

        return redirect()->route('admin.settings')->with('success', 'Informasi profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak sesuai.',
        ]);

        $user = Auth::user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password saat ini tidak sesuai.',
            ])->withInput();
        }

        $this->accountService->changePassword($user, $request->password);

        return redirect()->route('admin.account')->with('success', 'Password berhasil diperbarui.');
    }

    public function updateNotifications(Request $request)
    {
        $preferences = [
            'new_sales' => $request->boolean('new_sales'),
            'payout' => $request->boolean('payout'),
            'security_alerts' => $request->boolean('security_alerts'),
            'newsletter' => $request->boolean('newsletter'),
        ];

        $this->accountService->updateNotificationPreferences(Auth::user(), $preferences);

        return redirect()->route('admin.account')->with('success', 'Preferensi notifikasi berhasil disimpan.');
    }

    public function requestEmailChangeOtp(Request $request)
    {
        $user = Auth::user();

        // Cek cooldown 24 jam
        $rateLimitKey = 'email_change|'.$user->id;
        $hasDbCooldown = $user->last_email_change_requested_at
            && $user->last_email_change_requested_at->copy()->addDay()->isFuture();

        if (RateLimiter::tooManyAttempts($rateLimitKey, 1) || $hasDbCooldown) {
            $availableIn = RateLimiter::availableIn($rateLimitKey);
            $availableHours = max(1, ceil($availableIn / 3600));

            return back()->withErrors([
                'new_email' => "Anda sudah melakukan permintaan ganti email dalam 24 jam terakhir. Coba lagi dalam {$availableHours} jam.",
            ])->withInput();
        }

        // Validasi email baru
        $request->validate([
            'new_email' => 'required|email|max:255|unique:users,email',
        ], [
            'new_email.required' => 'Alamat email baru wajib diisi.',
            'new_email.email' => 'Format alamat email tidak valid.',
            'new_email.unique' => 'Alamat email ini sudah digunakan oleh akun lain.',
        ]);

        // Rate limit request OTP (maks 3x per 15 menit) agar tidak spam
        $otpRequestKey = 'otp_request|'.$user->id;
        if (RateLimiter::tooManyAttempts($otpRequestKey, 3)) {
            $seconds = RateLimiter::availableIn($otpRequestKey);

            return back()->withErrors([
                'new_email' => "Terlalu banyak permintaan OTP. Coba lagi dalam {$seconds} detik.",
            ])->withInput();
        }
        RateLimiter::hit($otpRequestKey, 900); // 15 menit

        // Generate OTP 6 digit
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $otpHash = Hash::make($otp);

        // Hapus OTP lama jika ada, simpan yang baru
        EmailChangeOtp::where('user_id', $user->id)->delete();
        EmailChangeOtp::create([
            'user_id' => $user->id,
            'new_email' => $request->new_email,
            'otp_hash' => $otpHash,
            'attempts' => 0,
            'expires_at' => now()->addMinutes(10),
        ]);

        // Kirim OTP ke email AKTIF saat ini (bukan email baru)
        try {
            Mail::to($user->email)->send(new EmailChangeOtpMail($otp, $user->name, $request->new_email));
        } catch (\Exception $e) {
            Log::error('Gagal mengirim OTP ganti email: '.$e->getMessage(), ['user_id' => $user->id]);
            EmailChangeOtp::where('user_id', $user->id)->delete();

            return back()->withErrors([
                'new_email' => 'Gagal mengirim kode OTP. Silakan coba beberapa saat lagi.',
            ])->withInput();
        }

        ActivityLogger::log(
            'email_change_otp_requested',
            "User {$user->name} meminta OTP untuk ganti email ke {$request->new_email}.",
            ['new_email' => $request->new_email],
            $user->id
        );

        // Simpan new_email ke session untuk ditampilkan di step 2
        session(['otp_target_email' => $request->new_email]);

        return redirect()->route('admin.account')->with(
            'otp_sent',
            "Kode OTP 6 digit telah dikirim ke email aktif Anda ({$user->email}). Kode berlaku selama 10 menit."
        );
    }

    public function verifyEmailChangeOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6|regex:/^[0-9]{6}$/',
        ], [
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.size' => 'Kode OTP harus terdiri dari 6 digit.',
            'otp.regex' => 'Kode OTP hanya boleh berisi angka.',
        ]);

        $user = Auth::user();
        $otpRecord = EmailChangeOtp::where('user_id', $user->id)->first();

        // Cek apakah OTP record ada
        if (! $otpRecord) {
            return back()->withErrors([
                'otp' => 'Tidak ada permintaan OTP aktif. Silakan mulai dari awal.',
            ]);
        }

        // Cek apakah OTP sudah kedaluwarsa
        if ($otpRecord->isExpired()) {
            $otpRecord->delete();

            return back()->withErrors([
                'otp' => 'Kode OTP sudah kedaluwarsa (lebih dari 10 menit). Silakan minta kode baru.',
            ]);
        }

        // Cek apakah sudah melebihi batas percobaan
        if ($otpRecord->isExceededAttempts()) {
            $otpRecord->delete();

            return back()->withErrors([
                'otp' => 'Kode OTP tidak valid terlalu banyak kali. Silakan minta kode baru.',
            ]);
        }

        // Verifikasi OTP
        if (! Hash::check($request->otp, $otpRecord->otp_hash)) {
            $otpRecord->increment('attempts');
            $remaining = 3 - $otpRecord->fresh()->attempts;

            ActivityLogger::log(
                'email_change_otp_failed',
                "User {$user->name} memasukkan OTP yang salah untuk ganti email.",
                ['attempts_remaining' => $remaining],
                $user->id
            );

            return back()->withErrors([
                'otp' => "Kode OTP salah. Sisa percobaan: {$remaining} kali.",
            ]);
        }

        // OTP valid — lanjutkan proses ganti email
        $newEmail = $otpRecord->new_email;

        // Cek sekali lagi apakah email baru masih unik
        if (User::where('email', $newEmail)->where('id', '!=', $user->id)->exists()) {
            $otpRecord->delete();

            return back()->withErrors([
                'otp' => 'Email tujuan sudah digunakan oleh akun lain. Silakan mulai dari awal dengan email baru.',
            ]);
        }

        // Hapus OTP record
        $otpRecord->delete();

        // Hapus pending lama, buat yang baru
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

            return back()->withErrors([
                'otp' => 'OTP valid, namun gagal mengirim email konfirmasi ke alamat baru. Silakan coba lagi.',
            ]);
        }

        // Update cooldown 24 jam
        $user->last_email_change_requested_at = now();
        $user->save();

        RateLimiter::hit('email_change|'.$user->id, 86400);
        session()->forget('otp_target_email');

        ActivityLogger::log(
            'email_change_otp_verified',
            "User {$user->name} berhasil verifikasi OTP dan email konfirmasi dikirim ke {$newEmail}.",
            ['new_email' => $newEmail],
            $user->id
        );

        return redirect()->route('admin.account')->with(
            'success',
            "OTP berhasil diverifikasi. Email konfirmasi telah dikirim ke {$newEmail}. Silakan periksa kotak masuk Anda."
        );
    }

    public function verifyEmailChange(string $token)
    {
        $pending = PendingEmailChange::where('token', $token)->first();

        if (! $pending) {
            return redirect()->route('admin.account')->withErrors([
                'email' => 'Tautan verifikasi email tidak valid atau sudah pernah digunakan.',
            ]);
        }

        if ($pending->isExpired()) {
            $pending->delete();

            return redirect()->route('admin.account')->withErrors([
                'email' => 'Tautan verifikasi email telah kadaluwarsa (lebih dari 24 jam). Silakan ajukan permintaan baru.',
            ]);
        }

        if (User::where('email', $pending->new_email)->where('id', '!=', $pending->user_id)->exists()) {
            $pending->delete();

            return redirect()->route('admin.account')->withErrors([
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

        $successMessage = $wasGoogleConnected
            ? 'Alamat email akun Anda berhasil diperbarui menjadi '.$newEmail.'. Koneksi Google Anda telah diputuskan secara otomatis karena email akun berubah. Silakan hubungkan kembali di halaman Layanan Terhubung jika diperlukan.'
            : 'Alamat email akun Anda berhasil diperbarui menjadi '.$newEmail.'.';

        return redirect()->route('admin.account')->with(
            'success',
            $successMessage
        );
    }

    public function disconnectGoogle()
    {
        $user = Auth::user();

        if (empty($user->password)) {
            return redirect()->route('admin.account')->withErrors([
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

        return redirect()->route('admin.account')->with('success', 'Akun Google berhasil diputuskan dari akun Anda.');
    }

    public function revokeSession(string $sessionId)
    {
        $currentSessionId = session()->getId();
        if ($sessionId === $currentSessionId) {
            return redirect()->route('admin.account')->withErrors([
                'session' => 'Anda tidak dapat mengakhiri sesi yang sedang aktif saat ini.',
            ]);
        }

        $deleted = DB::table('sessions')
            ->where('id', $sessionId)
            ->where('user_id', Auth::id())
            ->delete();

        if ($deleted) {
            ActivityLogger::log(
                'session_revoked',
                'User '.Auth::user()->name.' mengakhiri salah satu sesi login aktif.',
                ['revoked_session_id' => $sessionId],
                Auth::id()
            );
        }

        return redirect()->route('admin.account')->with('success', 'Sesi perangkat berhasil diakhiri.');
    }

    public function revokeAllSessions()
    {
        $currentSessionId = session()->getId();

        $deleted = DB::table('sessions')
            ->where('user_id', Auth::id())
            ->where('id', '!=', $currentSessionId)
            ->delete();

        ActivityLogger::log(
            'all_sessions_revoked',
            'User '.Auth::user()->name.' mengakhiri semua sesi login perangkat lain.',
            ['deleted_sessions_count' => $deleted],
            Auth::id()
        );

        return redirect()->route('admin.account')->with('success', 'Semua sesi di perangkat lain berhasil diakhiri.');
    }

    public function delete()
    {
        $this->accountService->deleteAccount(Auth::user());

        Auth::logout();
        session()->flush();

        return redirect('/')->with('success', 'Akun Anda telah berhasil dihapus (soft delete). Silakan daftar kembali jika ingin menggunakan layanan kami.');
    }

    private function parseBrowser(string $userAgent): string
    {
        if (str_contains($userAgent, 'Edg')) {
            return 'Microsoft Edge';
        }
        if (str_contains($userAgent, 'Chrome') && ! str_contains($userAgent, 'Edg')) {
            return 'Chrome';
        }
        if (str_contains($userAgent, 'Firefox')) {
            return 'Firefox';
        }
        if (str_contains($userAgent, 'Safari') && ! str_contains($userAgent, 'Chrome')) {
            return 'Safari';
        }
        if (str_contains($userAgent, 'Opera') || str_contains($userAgent, 'OPR')) {
            return 'Opera';
        }

        return 'Browser Lain';
    }

    private function parseDevice(string $userAgent): string
    {
        if (str_contains($userAgent, 'Mobile') || str_contains($userAgent, 'Android') || str_contains($userAgent, 'iPhone')) {
            return 'mobile';
        }
        if (str_contains($userAgent, 'Tablet') || str_contains($userAgent, 'iPad')) {
            return 'tablet';
        }

        return 'desktop';
    }
}
