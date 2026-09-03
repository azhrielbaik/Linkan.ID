<?php

namespace App\Http\Controllers\AdminSeller;

use App\Http\Controllers\Controller;
use App\Services\AdminSeller\AccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    protected $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function edit()
    {
        $user = Auth::user();
        return view('admin_seller.features.account.index', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'password.min' => 'Password minimal harus 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak sesuai'
        ]);

        $this->accountService->updateAccount(
            Auth::user(),
            $request->only(['username', 'name']),
            $request->input('password')
        );

        return redirect()->route('admin.account')->with('success', 'Account updated successfully.');
    }

    public function delete()
    {
        $this->accountService->deleteAccount(Auth::user());
        
        Auth::logout();
        session()->flush();
        
        return redirect('/')->with('success', 'Akun Anda telah berhasil dihapus (soft delete). Silakan daftar kembali jika ingin menggunakan layanan kami.');
    }
}
