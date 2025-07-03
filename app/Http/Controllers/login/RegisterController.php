<?php

namespace App\Http\Controllers\login;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    protected $redirectTo = '/dashboard';

    public function showRegistrationForm()
    {
        $googleData = session('google_data');
        return view('register', compact('googleData'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'username' => 'required|string|min:3|max:30|unique:users|alpha_dash',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $userData = [
            'name' => $request->name,
            'username' => strtolower($request->username),
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_link_active' => true,
            'role' => 'admin_seller'
        ];

        // Jika ada data dari Google, tambahkan google_id
        if ($request->has('google_id')) {
            $userData['google_id'] = $request->google_id;
        }

        User::create($userData);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
    }
} 