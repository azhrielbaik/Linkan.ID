<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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

    public function login(Request $request)
    {
        $lockoutKey = $this->lockoutKey($request);
        $attemptsKey = $this->attemptsKey($request);

        // Cek apakah sedang dalam masa lockout
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

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

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

            // Login berhasil — bersihkan seluruh rate limiter
            RateLimiter::clear($lockoutKey);
            RateLimiter::clear($attemptsKey);
            $request->session()->regenerate();

            DB::transaction(function () use ($user) {
                // Catat Log Aktivitas Login
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

        // Login gagal (kredensial salah)
        Log::channel('auth')->warning('Login failed: invalid credentials', [
            'email' => $request->input('email'),
            'ip' => $request->ip(),
            'reason' => 'invalid_credentials',
        ]);

        // Login gagal — akumulasikan percobaan (disimpan selama 5 menit / 300 detik agar tidak reset di tengah proses mencoba)
        RateLimiter::hit($attemptsKey, 300);

        $attempts = RateLimiter::attempts($attemptsKey);
        $maxAttempts = 5;

        if ($attempts >= $maxAttempts) {
            // Sudah 5 kali gagal — aktifkan lockout selama 30 detik & bersihkan counter percobaan
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

        // Restore locale to session so that login page displays in correct language
        session(['locale' => $locale]);

        return redirect()->route('login', ['locale' => $locale]);
    }

    public function redirectToGoogle()
    {
        session(['google_intent' => 'login']);

        return Socialite::driver('google')->redirect();
    }

    /**
     * Redirect ke halaman autentikasi Google dari halaman registrasi.
     */
    public function redirectToGoogleRegister()
    {
        session(['google_intent' => 'register']);

        return Socialite::driver('google')->redirect();
    }

    /**
     * Redirect ke halaman autentikasi Google untuk menghubungkan akun Google ke user saat ini.
     */
    public function redirectToGoogleConnect()
    {
        session(['google_intent' => 'connect']);

        return Socialite::driver('google')->redirect();
    }

    /**
     * Tangani callback dari Google OAuth (baik intent 'connect', 'register', maupun 'login').
     */
    public function handleGoogleCallback()
    {
        $intent = session()->pull('google_intent', 'login');

        if ($intent === 'connect') {
            return $this->handleGoogleConnectCallback();
        }

        if ($intent === 'register') {
            return $this->handleGoogleRegisterCallback();
        }

        return $this->handleGoogleLoginCallback();
    }

    /**
     * Menangani callback Google OAuth saat user ingin menghubungkan (connect) akun Google ke profilnya.
     */
    protected function handleGoogleConnectCallback()
    {
        if (! Auth::check()) {
            return redirect()->route('login')->withErrors([
                'email' => 'Sesi Anda telah berakhir. Silakan login kembali.',
            ]);
        }

        /** @var User $currentUser */
        $currentUser = Auth::user();

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            Log::channel('auth')->error('Google Connect OAuth Callback Error: '.$e->getMessage(), [
                'ip' => request()->ip(),
                'error_message' => $e->getMessage(),
            ]);

            ActivityLogger::log(
                'google_connect_failed',
                "User {$currentUser->name} ({$currentUser->email}) gagal menghubungkan akun Google: kendala komunikasi OAuth.",
                ['reason' => 'oauth_exception'],
                $currentUser->id
            );

            return redirect()->route('admin.account')
                ->withErrors(['google' => 'Terjadi kesalahan saat berkomunikasi dengan Google. Silakan coba lagi.'])
                ->with('error', 'Terjadi kesalahan saat berkomunikasi dengan Google. Silakan coba lagi.');
        }

        $googleEmail = strtolower(trim((string) ($googleUser->getEmail() ?? $googleUser->email)));
        $currentEmail = strtolower(trim((string) $currentUser->email));

        // 1. Validasi kesesuaian email Google dengan email akun saat ini
        if ($googleEmail !== $currentEmail) {
            ActivityLogger::log(
                'google_connect_failed',
                "User {$currentUser->name} ({$currentUser->email}) gagal menghubungkan akun Google: email Google ({$googleEmail}) tidak cocok dengan email akun ({$currentEmail}).",
                [
                    'google_email' => $googleEmail,
                    'user_email' => $currentEmail,
                    'reason' => 'email_mismatch',
                ],
                $currentUser->id
            );

            $errorMessage = "Akun Google ({$googleEmail}) tidak cocok dengan email akun Anda ({$currentEmail}).";

            return redirect()->route('admin.account')
                ->withErrors(['google' => $errorMessage])
                ->with('error', $errorMessage);
        }

        $googleId = (string) ($googleUser->getId() ?? $googleUser->id);

        // 2. Validasi apakah google_id ini sudah terhubung ke akun user lain
        $isAlreadyUsed = User::where('google_id', $googleId)
            ->where('id', '!=', $currentUser->id)
            ->exists();

        if ($isAlreadyUsed) {
            ActivityLogger::log(
                'google_connect_failed',
                "User {$currentUser->name} ({$currentUser->email}) gagal menghubungkan akun Google: Google ID ({$googleId}) sudah digunakan oleh akun lain.",
                [
                    'google_id' => $googleId,
                    'reason' => 'already_linked_to_another_account',
                ],
                $currentUser->id
            );

            $errorMessage = 'Akun Google ini sudah terhubung dengan akun lain.';

            return redirect()->route('admin.account')
                ->withErrors(['google' => $errorMessage])
                ->with('error', $errorMessage);
        }

        // 3. Simpan google_id ke akun pengguna yang sedang login
        $currentUser->google_id = $googleId;
        $currentUser->save();

        ActivityLogger::log(
            'google_connected',
            "User {$currentUser->name} ({$currentUser->email}) berhasil menghubungkan akun Google.",
            [
                'google_id' => $googleId,
                'google_email' => $googleEmail,
            ],
            $currentUser->id
        );

        return redirect()->route('admin.account')
            ->with('success', 'Akun Google berhasil dihubungkan ke akun Anda.');
    }

    /**
     * Menangani callback Google OAuth saat user melakukan proses login dari halaman login.
     */
    protected function handleGoogleLoginCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $googleId = (string) ($googleUser->getId() ?? $googleUser->id);
            $googleEmail = strtolower(trim((string) ($googleUser->getEmail() ?? $googleUser->email)));

            $user = DB::transaction(function () use ($googleId, $googleEmail, $googleUser) {
                // Coba cari user berdasarkan google_id
                $user = User::where('google_id', $googleId)->first();

                // Kalau tidak ditemukan, cek berdasarkan email
                if (! $user) {
                    $user = User::where('email', $googleEmail)->first();

                    // Kalau user sudah ada, update google_id-nya
                    if ($user) {
                        $user->update([
                            'google_id' => $googleId,
                        ]);
                    } else {
                        // User belum terdaftar
                        return null;
                    }
                }

                // Catat Log Aktivitas Login Google
                ActivityLogger::log(
                    'user_login',
                    "User {$user->name} ({$user->email}) berhasil login melalui Google OAuth.",
                    ['role' => $user->role, 'login_type' => 'google_oauth'],
                    $user->id
                );

                return $user;
            });

            // Jika akun belum terdaftar, jangan lakukan auto-register
            // Arahkan user ke halaman pendaftaran agar mendaftar terlebih dahulu
            if (! $user) {
                Log::channel('auth')->warning('Google OAuth login failed: account not registered', [
                    'email' => $googleEmail,
                    'ip' => request()->ip(),
                    'reason' => 'account_not_found',
                ]);

                // Simpan data dari Google ke flash session agar otomatis terisi di form pendaftaran
                session()->flash('google_data', [
                    'name' => $googleUser->getName() ?? $googleUser->name,
                    'email' => $googleEmail,
                    'google_id' => $googleId,
                ]);

                return redirect()->route('register')->with('warning', 'Akun Google Anda belum terdaftar. Silakan lakukan pendaftaran akun terlebih dahulu.');
            }

            Auth::login($user);

            if ($user->isSuspended()) {
                Log::channel('auth')->warning('Suspended user attempted login', [
                    'user_id' => $user->id,
                    'ip' => request()->ip(),
                ]);
            }

            Log::channel('auth')->info('Google OAuth login successful', [
                'user_id' => $user->id,
                'ip' => request()->ip(),
                'is_new_user' => false,
            ]);

            // Redirect berdasarkan role
            if ($user->role === 'admin_seller') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'admin_platform') {
                return redirect()->route('platform-admin.dashboard');
            }

            // Default redirect jika role tidak sesuai
            return redirect()->route('login')->with('error', 'Role tidak valid.');

        } catch (\Exception $e) {
            Log::channel('auth')->error('Google OAuth login failed', [
                'ip' => request()->ip(),
                'error_message' => $e->getMessage(),
            ]);

            return redirect()->route('login')->with('error', 'Terjadi kesalahan saat login dengan Google. Silakan coba lagi.');
        }
    }

    /**
     * Menangani callback Google OAuth saat user menginisiasi pendaftaran dari halaman registrasi.
     */
    protected function handleGoogleRegisterCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $googleId = (string) ($googleUser->getId() ?? $googleUser->id);
            $googleEmail = strtolower(trim((string) ($googleUser->getEmail() ?? $googleUser->email)));
            $googleName = trim((string) ($googleUser->getName() ?? $googleUser->name ?? ''));
            if (empty($googleName)) {
                $googleName = explode('@', $googleEmail)[0];
            }

            // Cek apakah akun sudah terdaftar
            $existingUser = User::where('google_id', $googleId)
                ->orWhere('email', $googleEmail)
                ->first();

            if ($existingUser) {
                if (! $existingUser->google_id) {
                    $existingUser->update(['google_id' => $googleId]);
                }

                Auth::login($existingUser);
                request()->session()->regenerate();

                if ($existingUser->isSuspended()) {
                    Log::channel('auth')->warning('Suspended user attempted login via google register', [
                        'user_id' => $existingUser->id,
                        'ip' => request()->ip(),
                    ]);
                }

                Log::channel('auth')->info('Google OAuth login existing user via register', [
                    'user_id' => $existingUser->id,
                    'ip' => request()->ip(),
                    'is_new_user' => false,
                ]);

                ActivityLogger::log(
                    'user_login',
                    "User {$existingUser->name} ({$existingUser->email}) berhasil login melalui Google OAuth.",
                    ['role' => $existingUser->role, 'login_type' => 'google_oauth'],
                    $existingUser->id
                );

                if ($existingUser->role === 'admin_seller') {
                    return redirect()->route('admin.dashboard')->with('info', 'Akun Google Anda sudah terdaftar. Anda berhasil masuk ke dashboard.');
                } elseif ($existingUser->role === 'admin_platform') {
                    return redirect()->route('platform-admin.dashboard');
                }

                return redirect()->route('login');
            }

            // User belum terdaftar: buat akun baru dengan nama profile default dari nama akun Google
            $baseUsername = Str::slug($googleName, '');
            if (empty($baseUsername)) {
                $baseUsername = Str::slug(explode('@', $googleEmail)[0], '');
            }
            if (empty($baseUsername) || strlen($baseUsername) < 3) {
                $baseUsername = 'user'.Str::lower(Str::random(4));
            }
            $baseUsername = substr($baseUsername, 0, 20);

            $username = $baseUsername;
            $counter = 1;
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername.$counter;
                $counter++;
            }

            $newUser = DB::transaction(function () use ($googleName, $googleEmail, $googleId, $username) {
                $user = User::create([
                    'name' => $googleName,
                    'email' => $googleEmail,
                    'username' => $username,
                    'password' => Hash::make(Str::random(32)),
                    'google_id' => $googleId,
                    'is_link_active' => true,
                    'role' => 'admin_seller',
                ]);

                ActivityLogger::log(
                    'user_register',
                    "Pengguna baru {$user->name} ({$user->email}) berhasil mendaftar akun seller via Google OAuth.",
                    ['username' => $user->username, 'role' => $user->role, 'register_type' => 'google_oauth'],
                    $user->id
                );

                ActivityLogger::log(
                    'user_login',
                    "User {$user->name} ({$user->email}) berhasil login melalui Google OAuth setelah pendaftaran.",
                    ['role' => $user->role, 'login_type' => 'google_oauth'],
                    $user->id
                );

                return $user;
            });

            Auth::login($newUser);
            request()->session()->regenerate();

            Log::channel('auth')->info('User registered and logged in via Google OAuth', [
                'user_id' => $newUser->id,
                'email' => $newUser->email,
                'role' => $newUser->role,
                'ip' => request()->ip(),
            ]);

            return redirect()->route('admin.dashboard')->with('success', 'Pendaftaran dengan Google berhasil! Selamat datang di dashboard Anda.');

        } catch (\Exception $e) {
            Log::channel('auth')->error('Google OAuth register failed', [
                'ip' => request()->ip(),
                'error_message' => $e->getMessage(),
            ]);

            return redirect()->route('register')->with('error', 'Terjadi kesalahan saat menghubungkan akun Google. Silakan coba lagi.');
        }
    }
}
