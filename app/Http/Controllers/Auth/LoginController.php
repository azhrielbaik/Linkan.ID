<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\ActivityLogger;
use App\Services\Auth\GoogleAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    protected $redirectTo = '/admin/dashboard';

    protected GoogleAuthService $googleAuthService;

    public function __construct(GoogleAuthService $googleAuthService)
    {
        $this->googleAuthService = $googleAuthService;
    }

    /**
     * Buat identifier unik berdasarkan kombinasi email dan IP.
     */
    protected function throttleKey(Request $request): string
    {
        return Str::lower(trim((string) $request->input('email', ''))).'|'.$request->ip();
    }

    /**
     * Kunci untuk status lockout.
     */
    protected function lockoutKey(Request $request): string
    {
        return 'login_lockout|'.$this->throttleKey($request);
    }

    /**
     * Kunci untuk menghitung akumulasi percobaan gagal.
     */
    protected function attemptsKey(Request $request): string
    {
        return 'login_attempts|'.$this->throttleKey($request);
    }

    public function showLoginForm()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'admin_seller') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'admin_platform') {
                return redirect()->route('platform-admin.dashboard');
            }
        }

        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $lockoutKey = $this->lockoutKey($request);
        $attemptsKey = $this->attemptsKey($request);

        if (RateLimiter::tooManyAttempts($lockoutKey, 1)) {
            $seconds = max(1, RateLimiter::availableIn($lockoutKey));

            Log::channel('auth')->warning('Login lockout active', [
                'email' => $request->input('email'),
                'ip' => $request->ip(),
                'lockout_seconds' => $seconds,
            ]);

            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login. Silakan tunggu {$seconds} detik sebelum mencoba kembali.",
            ])->with('lockout_seconds', $seconds)->onlyInput('email');
        }

        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->isSuspended()) {
                Log::channel('auth')->warning('Suspended user attempted login', [
                    'user_id' => $user->id,
                    'ip' => $request->ip(),
                ]);
            }

            Log::channel('auth')->info('User login successful', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            RateLimiter::clear($lockoutKey);
            RateLimiter::clear($attemptsKey);
            $request->session()->regenerate();

            DB::transaction(function () use ($user) {
                ActivityLogger::log(
                    'user_login',
                    "User {$user->name} ({$user->email}) berhasil login.",
                    ['role' => $user->role, 'login_type' => 'email_password'],
                    $user->id
                );
            });

            if ($user->role === 'admin_seller') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'admin_platform') {
                return redirect()->route('platform-admin.dashboard');
            }
        }

        Log::channel('auth')->warning('Login failed: invalid credentials', [
            'email' => $request->input('email'),
            'ip' => $request->ip(),
            'reason' => 'invalid_credentials',
        ]);

        RateLimiter::hit($attemptsKey, 300);

        $attempts = RateLimiter::attempts($attemptsKey);
        $maxAttempts = 5;

        if ($attempts >= $maxAttempts) {
            RateLimiter::hit($lockoutKey, 30);
            RateLimiter::clear($attemptsKey);

            $seconds = max(1, RateLimiter::availableIn($lockoutKey));

            Log::channel('auth')->warning('Login lockout triggered', [
                'email' => $request->input('email'),
                'ip' => $request->ip(),
                'lockout_seconds' => $seconds,
            ]);

            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login. Silakan tunggu {$seconds} detik sebelum mencoba kembali.",
            ])->with('lockout_seconds', $seconds)->onlyInput('email');
        }

        $remaining = $maxAttempts - $attempts;

        return back()->withErrors([
            'email' => "Email atau password yang Anda masukkan salah. Sisa percobaan: {$remaining} kali.",
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $locale = in_array($request->segment(1), ['id', 'en'])
            ? $request->segment(1)
            : session('locale', config('app.locale', 'id'));

        if ($user = Auth::user()) {
            Log::channel('auth')->info('User logged out', [
                'user_id' => $user->id,
                'ip' => $request->ip(),
            ]);

            ActivityLogger::log(
                'user_logout',
                "User {$user->name} ({$user->email}) telah logout dari sesi aktif.",
                ['role' => $user->role],
                $user->id
            );
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        session(['locale' => $locale]);

        return redirect()->route('login', ['locale' => $locale]);
    }

    public function redirectToGoogle()
    {
        return $this->googleAuthService->redirect('login');
    }

    public function redirectToGoogleRegister()
    {
        return $this->googleAuthService->redirect('register');
    }

    public function redirectToGoogleConnect()
    {
        return $this->googleAuthService->redirect('connect');
    }

    public function handleGoogleCallback()
    {
        return $this->googleAuthService->handleCallback();
    }
}
