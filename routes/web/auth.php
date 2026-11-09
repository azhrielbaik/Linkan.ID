<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

// Auth routes (within locale group)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1')->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

use App\Http\Controllers\Auth\GoogleAuthController;

// Google OAuth
Route::get('login/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('register/google', [GoogleAuthController::class, 'redirectToGoogleRegister'])->name('google.register');
Route::get('login/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('google.callback');
Route::get('/auth/google/connect', [GoogleAuthController::class, 'redirectToGoogleConnect'])->middleware('auth')->name('google.connect');

// Password Reset via Admin Platform OTP (4-Step Flow)
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'requestOtp'])->middleware('throttle:5,1')->name('password.request-otp');
Route::get('/verify-otp', [ForgotPasswordController::class, 'showVerifyOtpForm'])->name('password.verify-otp');
Route::post('/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->middleware('throttle:5,1')->name('password.verify-otp.submit');
Route::post('/verify-otp/resend', [ForgotPasswordController::class, 'resendOtp'])->middleware('throttle:5,1')->name('password.verify-otp.resend');
Route::get('/verify-otp/status', [ForgotPasswordController::class, 'checkOtpStatus'])->middleware('throttle:5,1')->name('password.otp.status');
Route::get('/create-new-password', [ForgotPasswordController::class, 'showCreatePasswordForm'])->name('password.create-new');
Route::post('/create-new-password', [ForgotPasswordController::class, 'submitCreatePassword'])->name('password.create-new.submit');
Route::get('/password-reset-success', [ForgotPasswordController::class, 'showSuccessPage'])->name('password.success');
