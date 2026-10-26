<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\ActivityLogger;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthService
{
    public function redirect(string $intent): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        session(['google_intent' => $intent]);

        return Socialite::driver('google')->redirect();
    }

    public function handleCallback(): RedirectResponse
    {
        $intent = session()->pull('google_intent', 'login');

        if ($intent === 'connect') {
            return $this->handleConnect();
        }

        if ($intent === 'register') {
            return $this->handleRegister();
        }

        return $this->handleLogin();
    }

    public function handleConnect(): RedirectResponse
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
        } catch (Exception $e) {
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

    public function handleLogin(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $googleId = (string) ($googleUser->getId() ?? $googleUser->id);
            $googleEmail = strtolower(trim((string) ($googleUser->getEmail() ?? $googleUser->email)));

            $user = DB::transaction(function () use ($googleId, $googleEmail) {
                $user = User::where('google_id', $googleId)->first();

                if (! $user) {
                    $user = User::where('email', $googleEmail)->first();

                    if ($user) {
                        $user->update([
                            'google_id' => $googleId,
                        ]);
                    } else {
                        return null;
                    }
                }

                ActivityLogger::log(
                    'user_login',
                    "User {$user->name} ({$user->email}) berhasil login melalui Google OAuth.",
                    ['role' => $user->role, 'login_type' => 'google_oauth'],
                    $user->id
                );

                return $user;
            });

            if (! $user) {
                Log::channel('auth')->warning('Google OAuth login failed: account not registered', [
                    'email' => $googleEmail,
                    'ip' => request()->ip(),
                    'reason' => 'account_not_found',
                ]);

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

            if ($user->role === 'admin_seller') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'admin_platform') {
                return redirect()->route('platform-admin.dashboard');
            }

            return redirect()->route('login')->with('error', 'Role tidak valid.');

        } catch (Exception $e) {
            Log::channel('auth')->error('Google OAuth login failed', [
                'ip' => request()->ip(),
                'error_message' => $e->getMessage(),
            ]);

            return redirect()->route('login')->with('error', 'Terjadi kesalahan saat login dengan Google. Silakan coba lagi.');
        }
    }

    public function handleRegister(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $googleId = (string) ($googleUser->getId() ?? $googleUser->id);
            $googleEmail = strtolower(trim((string) ($googleUser->getEmail() ?? $googleUser->email)));
            $googleName = trim((string) ($googleUser->getName() ?? $googleUser->name ?? ''));
            if (empty($googleName)) {
                $googleName = explode('@', $googleEmail)[0];
            }

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

        } catch (Exception $e) {
            Log::channel('auth')->error('Google OAuth register failed', [
                'ip' => request()->ip(),
                'error_message' => $e->getMessage(),
            ]);

            return redirect()->route('register')->with('error', 'Terjadi kesalahan saat menghubungkan akun Google. Silakan coba lagi.');
        }
    }
}
