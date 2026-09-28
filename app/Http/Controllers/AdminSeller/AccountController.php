<?php

namespace App\Http\Controllers\AdminSeller;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AdminSeller\AccountService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        [$emailChangeCooldown, $emailChangeCooldownSeconds] = $this->accountService->getEmailChangeCooldown($user);

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
        $request->validate([
            'new_email' => 'required|email|max:255|unique:users,email',
        ], [
            'new_email.required' => 'Alamat email baru wajib diisi.',
            'new_email.email' => 'Format alamat email tidak valid.',
            'new_email.unique' => 'Alamat email ini sudah digunakan oleh akun lain.',
        ]);

        $user = Auth::user();
        $this->accountService->requestEmailChangeOtp($user, $request->new_email);

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
        $newEmail = $this->accountService->verifyEmailChangeOtp($user, $request->otp);

        session()->forget('otp_target_email');

        return redirect()->route('admin.account')->with(
            'success',
            "OTP berhasil diverifikasi. Email konfirmasi telah dikirim ke {$newEmail}. Silakan periksa kotak masuk Anda."
        );
    }

    public function verifyEmailChange(string $token)
    {
        $result = $this->accountService->verifyEmailChange($token);

        return redirect()->route('admin.account')->with(
            'success',
            $result['message']
        );
    }

    public function disconnectGoogle()
    {
        $user = Auth::user();
        $this->accountService->disconnectGoogle($user);

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
