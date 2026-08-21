<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetRequest;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    /**
     * Step 1: Halaman Formulir Masukkan Email.
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Step 1 -> Step 2: Kirim permintaan reset password ke Admin Platform.
     */
    public function requestOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ], [
            'email.required' => 'Email address is required.',
            'email.email'    => 'Please enter a valid email address.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withInput()->withErrors([
                'email' => 'No account found with this email address.',
            ]);
        }

        // Cek apakah sudah ada permohonan pending aktif
        $existing = PasswordResetRequest::where('email', $request->email)
            ->where('status', 'pending')
            ->first();

        if (!$existing) {
            PasswordResetRequest::create([
                'user_id' => $user->id,
                'email'   => $user->email,
                'reason'  => 'Permintaan reset password seller',
                'status'  => 'pending',
            ]);
        }

        return redirect()->route('password.verify-otp', ['email' => $request->email]);
    }

    /**
     * Step 2: Halaman Verifikasi Kode OTP (4 Digit).
     */
    public function showVerifyOtpForm(Request $request)
    {
        $email = $request->query('email') ?? session('reset_email');
        if (!$email) {
            return redirect()->route('password.request');
        }

        $latestRequest = PasswordResetRequest::where('email', $email)
            ->latest()
            ->first();

        return view('auth.verify-otp', [
            'email'         => $email,
            'latestRequest' => $latestRequest,
        ]);
    }

    /**
     * API Status Check untuk Auto-Fill OTP saat Admin Setujui.
     */
    public function checkOtpStatus(Request $request)
    {
        $email = $request->query('email');
        if (!$email) {
            return response()->json(['status' => 'error'], 400);
        }

        $req = PasswordResetRequest::where('email', $email)
            ->latest()
            ->first();

        if (!$req) {
            return response()->json(['status' => 'none']);
        }

        if ($req->status === 'approved' && $req->isExpired()) {
            return response()->json(['status' => 'expired']);
        }

        return response()->json([
            'status'   => $req->status,
            'otp_code' => $req->status === 'approved' ? $req->otp_code : null,
        ]);
    }

    /**
     * Step 2 -> Step 3: Verifikasi Kode OTP.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'otp_code' => 'required|string|size:4',
        ], [
            'otp_code.required' => 'Please enter the 4-digit code.',
            'otp_code.size'     => 'The verification code must be 4 digits.',
        ]);

        $resetReq = PasswordResetRequest::where('email', $request->email)
            ->where('status', 'approved')
            ->latest()
            ->first();

        if (!$resetReq) {
            return back()->withInput()->withErrors([
                'otp_code' => 'Permintaan reset belum disetujui Admin atau kode telah kedaluwarsa.',
            ]);
        }

        if ($resetReq->isExpired()) {
            return back()->withInput()->withErrors([
                'otp_code' => 'Kode verifikasi telah kedaluwarsa. Silakan minta kode baru.',
            ]);
        }

        if (trim($resetReq->otp_code) !== trim($request->otp_code)) {
            return back()->withInput()->withErrors([
                'otp_code' => 'Kode OTP salah. Silakan periksa kembali.',
            ]);
        }

        // Simpan sesi verifikasi OTP untuk step 3
        session([
            'otp_verified_email' => $request->email,
            'otp_request_id'     => $resetReq->id,
        ]);

        return redirect()->route('password.create-new');
    }

    /**
     * Step 3: Halaman Buat Password Baru.
     */
    public function showCreatePasswordForm(Request $request)
    {
        $email = session('otp_verified_email') ?? $request->query('email');
        if (!$email) {
            return redirect()->route('password.request');
        }

        return view('auth.create-new-password', [
            'email' => $email,
        ]);
    }

    /**
     * Step 3 -> Step 4: Simpan Password Baru.
     */
    public function submitCreatePassword(Request $request)
    {
        $email = session('otp_verified_email') ?? $request->input('email');

        $request->validate([
            'password'              => 'required|string|min:8|same:password_confirmation',
            'password_confirmation' => 'required|string|min:8',
        ], [
            'password.required' => 'Please enter a new password.',
            'password.min'      => 'Password must be at least 8 characters.',
            'password.same'     => 'Password confirmation does not match.',
        ]);

        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->route('password.request')->withErrors(['email' => 'User not found.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Tandai permohonan selesai
        if ($reqId = session('otp_request_id')) {
            PasswordResetRequest::where('id', $reqId)->update([
                'status'      => 'completed',
                'resolved_at' => now(),
            ]);
        } else {
            PasswordResetRequest::where('email', $email)
                ->where('status', 'approved')
                ->latest()
                ->update([
                    'status'      => 'completed',
                    'resolved_at' => now(),
                ]);
        }

        // Catat di log
        ActivityLogger::log(
            'password_reset_success',
            "User {$user->name} ({$user->email}) berhasil memperbarui password baru.",
            ['user_id' => $user->id, 'email' => $user->email]
        );

        // Hapus session temporary
        session()->forget(['otp_verified_email', 'otp_request_id']);

        return redirect()->route('password.success');
    }

    /**
     * Step 4: Halaman Sukses Password Berhasil Diubah.
     */
    public function showSuccessPage()
    {
        return view('auth.password-reset-success');
    }
}