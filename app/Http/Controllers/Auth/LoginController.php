<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    protected $redirectTo = '/admin/dashboard';

    /**
     * Buat identifier unik berdasarkan kombinasi email dan IP.
     */
    protected function throttleKey(Request $request): string
    {
        return Str::lower(trim((string) $request->input('email', ''))) . '|' . $request->ip();
    }

    /**
     * Kunci untuk status lockout.
     */
    protected function lockoutKey(Request $request): string
    {
        return 'login_lockout|' . $this->throttleKey($request);
    }

    /**
     * Kunci untuk menghitung akumulasi percobaan gagal.
     */
    protected function attemptsKey(Request $request): string
    {
        return 'login_attempts|' . $this->throttleKey($request);
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

    public function login(Request $request)
    {
        $lockoutKey = $this->lockoutKey($request);
        $attemptsKey = $this->attemptsKey($request);

        // Cek apakah sedang dalam masa lockout
        if (RateLimiter::tooManyAttempts($lockoutKey, 1)) {
            $seconds = max(1, RateLimiter::availableIn($lockoutKey));
            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login. Silakan tunggu {$seconds} detik sebelum mencoba kembali.",
            ])->with('lockout_seconds', $seconds)->onlyInput('email');
        }

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Login berhasil — bersihkan seluruh rate limiter
            RateLimiter::clear($lockoutKey);
            RateLimiter::clear($attemptsKey);
            $request->session()->regenerate();

            // Catat Log Aktivitas Login
            ActivityLogger::log(
                'user_login',
                "User {$user->name} ({$user->email}) berhasil login.",
                ['role' => $user->role, 'login_type' => 'email_password'],
                $user->id
            );

            if ($user->role === 'admin_seller') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'admin_platform') {
                return redirect()->route('platform-admin.dashboard');
            }
        }

        // Login gagal — akumulasikan percobaan (disimpan selama 5 menit / 300 detik agar tidak reset di tengah proses mencoba)
        RateLimiter::hit($attemptsKey, 300);

        $attempts = RateLimiter::attempts($attemptsKey);
        $maxAttempts = 5;

        if ($attempts >= $maxAttempts) {
            // Sudah 5 kali gagal — aktifkan lockout selama 30 detik & bersihkan counter percobaan
            RateLimiter::hit($lockoutKey, 30);
            RateLimiter::clear($attemptsKey);

            $seconds = max(1, RateLimiter::availableIn($lockoutKey));
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
        if ($user = Auth::user()) {
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

        return redirect('/login');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            \Log::info('Google User Data: ', [
                'id' => $googleUser->id,
                'name' => $googleUser->name,
                'email' => $googleUser->email
            ]);
            // Coba cari user berdasarkan google_id
            $user = User::where('google_id', $googleUser->id)->first();

            // Kalau tidak ditemukan, cek berdasarkan email
            if (!$user) {
                $user = User::where('email', $googleUser->email)->first();

                // Kalau user sudah ada, update google_id-nya
                if ($user) {
                    $user->update([
                        'google_id' => $googleUser->id,
                    ]);
                } else {
                    // Kalau user belum ada, redirect ke halaman register dengan data Google
                    return redirect()->route('register')->with([
                        'google_data' => [
                            'name' => $googleUser->name,
                            'email' => $googleUser->email,
                            'google_id' => $googleUser->id
                        ],
                        'error' => 'Email Anda belum terdaftar. Silakan lengkapi data untuk mendaftar.'
                    ]);
                }
            }

            Auth::login($user);
            
            // Catat Log Aktivitas Login Google
            ActivityLogger::log(
                'user_login',
                "User {$user->name} ({$user->email}) berhasil login melalui Google OAuth.",
                ['role' => $user->role, 'login_type' => 'google_oauth'],
                $user->id
            );

            // Redirect berdasarkan role
            if ($user->role === 'admin_seller') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'admin_platform') {
                return redirect()->route('platform-admin.dashboard');
            }
            
            // Default redirect jika role tidak sesuai
            return redirect()->route('login')->with('error', 'Role tidak valid.');
            
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Terjadi kesalahan saat login dengan Google. Silakan coba lagi.');
        }
    }
}
