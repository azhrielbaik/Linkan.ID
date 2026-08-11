<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    protected $redirectTo = '/admin/dashboard';

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
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->role === 'admin_seller') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'admin_platform') {
                return redirect()->route('platform-admin.dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
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
