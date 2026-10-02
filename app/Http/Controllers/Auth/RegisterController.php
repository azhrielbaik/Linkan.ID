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

class RegisterController extends Controller
{
    protected $redirectTo = '/dashboard';

    public function showRegistrationForm()
    {
        $googleData = session('google_data');

        return view('auth.register', compact('googleData'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'username' => 'required|string|min:3|max:30|unique:users|alpha_dash',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $userData = [
            'name' => $request->name,
            'username' => strtolower($request->username),
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_link_active' => true,
            'role' => 'admin_seller',
        ];

        // Jika ada data dari Google, tambahkan google_id
        if ($request->has('google_id')) {
            $userData['google_id'] = $request->google_id;
        }

        $newUser = DB::transaction(function () use ($userData) {
            $user = User::create($userData);

            // Catat Log Registrasi Pengguna Baru
            ActivityLogger::log(
                'user_register',
                "Pengguna baru {$user->name} ({$user->email}) berhasil mendaftar akun seller.",
                ['username' => $user->username, 'role' => $user->role],
                $user->id
            );

            return $user;
        });

        // Auto login setelah registrasi
        Auth::login($newUser);

        Log::channel('auth')->info('User registered and logged in', [
            'user_id' => $newUser->id,
            'email' => $newUser->email,
            'role' => $newUser->role,
            'ip' => $request->ip(),
            'has_google' => ! empty($newUser->google_id),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Registrasi berhasil! Selamat datang di dashboard Anda.');
    }
}
