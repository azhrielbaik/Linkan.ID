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
        $locale = in_array($request->segment(1), ['id', 'en'])
            ? $request->segment(1)
            : session('locale', config('app.locale', 'id'));

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

        // Restore locale to session so that login page displays in correct language
        session(['locale' => $locale]);

        return redirect()->route('login', ['locale' => $locale]);
    }

    public function redirectToGoogle()
    {
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
     * Tangani callback dari Google OAuth (baik intent 'connect' maupun 'login').
     */
    public function handleGoogleCallback()
    {
        $intent = session()->pull('google_intent', 'login');

        if ($intent === 'connect') {
            return $this->handleGoogleConnectCallback();
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
            Log::error('Google Connect OAuth Callback Error: '.$e->getMessage());

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
     * Menangani callback Google OAuth saat user melakukan proses login/registrasi standar.
     */
    protected function handleGoogleLoginCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            Log::info('Google User Data: ', [
                'id' => $googleUser->id,
                'name' => $googleUser->name,
                'email' => $googleUser->email,
            ]);
            $user = DB::transaction(function () use ($googleUser) {
                // Coba cari user berdasarkan google_id
                $user = User::where('google_id', $googleUser->id)->first();

                // Kalau tidak ditemukan, cek berdasarkan email
                if (! $user) {
                    $user = User::where('email', $googleUser->email)->first();

                    // Kalau user sudah ada, update google_id-nya
                    if ($user) {
                        $user->update([
                            'google_id' => $googleUser->id,
                        ]);
                    } else {
                        // User akan direturn null dan dihandle di luar transaksi untuk redirect
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

            if (! $user) {
                // Auto-register user
                $baseUsername = Str::slug($googleUser->name, '');
                if (empty($baseUsername)) {
                    $baseUsername = explode('@', $googleUser->email)[0];
                }
                $username = $baseUsername;
                $counter = 1;
                while (User::where('username', $username)->exists()) {
                    $username = $baseUsername.$counter;
                    $counter++;
                }

                $user = DB::transaction(function () use ($googleUser, $username) {
                    $newUser = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'username' => strtolower($username),
                        'password' => Hash::make(Str::random(24)),
                        'google_id' => $googleUser->id,
                        'is_link_active' => true,
                        'role' => 'admin_seller',
                    ]);

                    ActivityLogger::log(
                        'user_register',
                        "Pengguna baru {$newUser->name} ({$newUser->email}) mendaftar via Google.",
                        ['username' => $newUser->username, 'role' => $newUser->role, 'login_type' => 'google_oauth'],
                        $newUser->id
                    );

                    return $newUser;
                });
            }

            Auth::login($user);

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
