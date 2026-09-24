<?php

namespace App\Http\Controllers\AdminSeller;

use App\Http\Controllers\Controller;
use App\Mail\EmailChangeVerificationMail;
use App\Models\PendingEmailChange;
use App\Models\User;
use App\Services\ActivityLogger;
use App\Services\AdminSeller\AccountService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
            'new_sales'       => true,
            'payout'          => true,
            'security_alerts' => true,
            'newsletter'      => false,
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
                'id'                => $session->id,
                'ip_address'        => $session->ip_address,
                'user_agent'        => $userAgent,
                'browser'           => $this->parseBrowser($userAgent),
                'device'            => $this->parseDevice($userAgent),
                'last_activity'     => $session->last_activity,
                'last_active_human' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                'is_current'        => ($session->id === $currentSessionId),
            ];
        });

        // 5 most recent login logs from activity_logs table
        $loginHistory = DB::table('activity_logs')
            ->where('user_id', $user->id)
            ->where('action', 'user_login')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('admin_seller.features.account.index', compact('user', 'notifPrefs', 'sessions', 'loginHistory'));
    }

    public function update(Request $request)
    {
        $rules = [
            'username'      => 'required|string|max:255',
            'name'          => 'required|string|max:255',
            'bio'           => 'nullable|string|max:255',
            'avatar'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_avatar' => 'nullable|boolean',
        ];

        if ($request->filled('password')) {
            $rules['current_password'] = 'required|string';
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules, [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.min'              => 'Password minimal harus 8 karakter.',
            'password.confirmed'        => 'Konfirmasi password tidak sesuai.',
        ]);

        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, Auth::user()->password)) {
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
            'password'         => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.required'         => 'Password baru wajib diisi.',
            'password.min'              => 'Password baru minimal harus 8 karakter.',
            'password.confirmed'        => 'Konfirmasi password baru tidak sesuai.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
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
            'new_sales'       => $request->boolean('new_sales'),
            'payout'          => $request->boolean('payout'),
            'security_alerts' => $request->boolean('security_alerts'),
            'newsletter'      => $request->boolean('newsletter'),
        ];

        $this->accountService->updateNotificationPreferences(Auth::user(), $preferences);

        return redirect()->route('admin.account')->with('success', 'Preferensi notifikasi berhasil disimpan.');
    }

    public function requestEmailChange(Request $request)
    {
        $request->validate([
            'new_email'        => 'required|email|max:255|unique:users,email',
            'password_confirm' => 'required|string',
        ], [
            'new_email.required'        => 'Alamat email baru wajib diisi.',
            'new_email.email'           => 'Format alamat email tidak valid.',
            'new_email.unique'          => 'Alamat email ini sudah digunakan oleh akun lain.',
            'password_confirm.required' => 'Konfirmasi password wajib diisi untuk verifikasi keamanan.',
        ]);

        $user = Auth::user();

        if ($user->password && !Hash::check($request->password_confirm, $user->password)) {
            return back()->withErrors([
                'password_confirm' => 'Password konfirmasi tidak sesuai.',
            ])->withInput();
        }

        // Hapus permintaan pending sebelumnya jika ada
        PendingEmailChange::where('user_id', $user->id)->delete();

        $token = Str::random(64);
        PendingEmailChange::create([
            'user_id'    => $user->id,
            'new_email'  => $request->new_email,
            'token'      => $token,
            'expires_at' => now()->addHours(24),
        ]);

        $verificationUrl = route('admin.account.email.verify', $token);

        try {
            Mail::to($request->new_email)->send(new EmailChangeVerificationMail($verificationUrl, $user->name));
        } catch (\Exception $e) {
            // Log warning but continue
            \Illuminate\Support\Facades\Log::warning('Failed sending email change verification: ' . $e->getMessage());
        }

        ActivityLogger::log(
            'request_email_change',
            "User {$user->name} meminta penggantian email akun ke {$request->new_email}.",
            ['new_email' => $request->new_email],
            $user->id
        );

        return redirect()->route('admin.account')->with(
            'success', 
            'Email konfirmasi telah dikirim ke ' . $request->new_email . '. Silakan periksa kotak masuk atau folder spam email baru Anda.'
        );
    }

    public function verifyEmailChange(string $token)
    {
        $pending = PendingEmailChange::where('token', $token)->first();

        if (!$pending) {
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

        $user->email = $newEmail;
        $user->email_verified_at = now();
        $user->save();

        $pending->delete();

        ActivityLogger::log(
            'email_changed',
            "User {$user->name} berhasil mengubah alamat email dari {$oldEmail} ke {$newEmail}.",
            ['old_email' => $oldEmail, 'new_email' => $newEmail],
            $user->id
        );

        return redirect()->route('admin.account')->with(
            'success', 
            'Alamat email akun Anda berhasil diperbarui menjadi ' . $newEmail . '.'
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
                "User " . Auth::user()->name . " mengakhiri salah satu sesi login aktif.",
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
            "User " . Auth::user()->name . " mengakhiri semua sesi login perangkat lain.",
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
        if (str_contains($userAgent, 'Edg')) return 'Microsoft Edge';
        if (str_contains($userAgent, 'Chrome') && !str_contains($userAgent, 'Edg')) return 'Chrome';
        if (str_contains($userAgent, 'Firefox')) return 'Firefox';
        if (str_contains($userAgent, 'Safari') && !str_contains($userAgent, 'Chrome')) return 'Safari';
        if (str_contains($userAgent, 'Opera') || str_contains($userAgent, 'OPR')) return 'Opera';
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
