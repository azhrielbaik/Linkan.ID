<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PasswordResetOtpRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Models\ActivityLog;
use App\Models\PasswordResetRequest;
use App\Services\Auth\PasswordResetOtpService;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    protected PasswordResetOtpService $otpService;

    public function __construct(PasswordResetOtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Step 1: Halaman Formulir Masukkan Email.
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Step 1 -> Step 2: Kirim kode OTP reset password langsung ke email pengguna (SMTP).
     */
    public function requestOtp(PasswordResetOtpRequest $request)
    {
        $result = $this->otpService->requestOtp($request->email, $request->ip());

        if (isset($result['token'])) {
            session([
                'reset_token' => $result['token'],
                'reset_request_id' => $result['reset_request_id'],
            ]);

            return redirect()->route('password.verify-otp', ['token' => $result['token']])
                ->with('status', $result['message']);
        }

        return back()->withInput()->with('status', $result['message']);
    }

    /**
     * Step 2: Halaman Verifikasi Kode OTP (4 Digit).
     */
    public function showVerifyOtpForm(Request $request)
    {
        $token = $request->query('token') ?? session('reset_token');
        $verifyData = $this->otpService->getVerifyData($token);

        if (! $verifyData) {
            return redirect()->route('password.request')
                ->with('status', 'Tautan reset password tidak valid atau sudah kedaluwarsa.');
        }

        return view('auth.verify-otp', $verifyData);
    }

    public function resendOtp(Request $request)
    {
        $request->validate(['token' => 'required|string|size:64']);

        $result = $this->otpService->resendOtp($request->token, $request->ip());

        if (! empty($result['invalid'])) {
            return redirect()->route('password.request')->with('status', $result['message']);
        }

        if (! empty($result['token']) && ! empty($result['reset_request_id'])) {
            session([
                'reset_token' => $result['token'],
                'reset_request_id' => $result['reset_request_id'],
            ]);
        }

        return redirect()->route('password.verify-otp', ['token' => $result['token']])
            ->with('status', $result['message']);
    }

    /**
     * API Status Check untuk Auto-Fill OTP saat Admin Setujui.
     */
    public function checkOtpStatus(Request $request)
    {
        $status = $this->otpService->checkOtpStatus($request->query('token'));

        if ($status === 'error') {
            return response()->json(['status' => 'error'], 400);
        }

        return response()->json(['status' => $status]);
    }

    /**
     * Step 2 -> Step 3: Verifikasi Kode OTP.
     */
    public function verifyOtp(VerifyOtpRequest $request)
    {
        $result = $this->otpService->verifyOtp($request->token, $request->otp_code, $request->ip());

        if (! $result['success']) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                ], 422);
            }

            return back()->withInput()->withErrors([
                'otp_code' => $result['message'],
            ]);
        }

        session(['otp_request_id' => $result['reset_request_id']]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'redirect' => route('password.create-new'),
            ]);
        }

        return redirect()->route('password.create-new');
    }

    /**
     * Step 3: Halaman Buat Password Baru.
     */
    public function showCreatePasswordForm(Request $request)
    {
        $resetRequest = PasswordResetRequest::with('user')->find(session('otp_request_id'));
        if (! $resetRequest || $resetRequest->status !== 'approved' || $resetRequest->isExpired() || $resetRequest->used_at) {
            return redirect()->route('password.request');
        }

        return view('auth.create-new-password', [
            'email' => ActivityLog::maskEmail($resetRequest->email),
        ]);
    }

    /**
     * Step 3 -> Step 4: Simpan Password Baru.
     */
    public function submitCreatePassword(ResetPasswordRequest $request)
    {
        $requestId = (int) session('otp_request_id');
        $result = $this->otpService->resetPassword($requestId, $request->password);

        if (! $result['success']) {
            return redirect()->route('password.request')->withErrors(['email' => $result['message']]);
        }

        session()->forget(['reset_token', 'reset_request_id', 'otp_request_id']);

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
