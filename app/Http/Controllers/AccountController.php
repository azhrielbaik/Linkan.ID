<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'password.min' => 'Password minimal harus 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak sesuai'
        ]);

        // Ambil user yang sedang login
        $user = Auth::user();

        // Update data user
        $user->username = $request->username;
        $user->name = $request->name;

        // Jika password diisi, update password
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('admin.account')->with('success', 'Account updated successfully.');
    }

    public function edit()
    {
        $user = Auth::user(); // Ambil data user yang sedang login
        return view('homeadminS.myaccount', compact('user'));
    }

    public function delete()
    {
        $user = Auth::user();
        
        // Soft delete data terkait user jika model mendukung soft delete
        // Appearance (tidak pakai soft delete, tetap pakai delete biasa)
        \App\Models\Appearance::where('user_id', $user->id)->delete();
        
        // DigitalProduct (pakai soft delete)
        \App\Models\DigitalProduct::where('user_id', $user->id)->delete();
        
        // Soft delete user
        $user->delete();
        
        // Logout user
        Auth::logout();
        
        // Hapus semua session
        session()->flush();
        
        // Redirect ke halaman landing page
        return redirect('/')->with('success', 'Akun Anda telah berhasil dihapus (soft delete). Silakan daftar kembali jika ingin menggunakan layanan kami.');
    }
}