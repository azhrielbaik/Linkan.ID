<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class LoginController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

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
                return redirect()->route('beranda.admins');
            } else if ($user->role === 'admin_platform') {
                return redirect()->route('beranda.platformadmin');
            }
            
            // Default redirect jika role tidak sesuai
            return redirect()->route('login')->with('error', 'Role tidak valid.');
            
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Terjadi kesalahan saat login dengan Google. Silakan coba lagi.');
        }
    }
}
