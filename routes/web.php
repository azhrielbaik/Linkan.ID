<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppearanceController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController as GoogleLoginController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DigitalProductController;
use App\Http\Controllers\login\LoginController;
use App\Http\Controllers\login\RegisterController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PayoutController;
use App\Http\Controllers\PlatformAdmin\VerifikasiController;
use App\Http\Controllers\PlatformAdminController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ShortlinkController;
use App\Http\Controllers\StatisticController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Halaman Utama
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Route for changing language
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

// Route untuk tracking link (harus di atas route lain yang menggunakan parameter)
Route::get('/linkan.id/{username}', [PublicPageController::class, 'show'])->name('track.view');
Route::get('/track-click', [DashboardController::class, 'trackClick'])->name('track.click');

// Public Profile
Route::get('/profile/{username}', [PublicPageController::class, 'show'])->name('public.profile');

Route::get('/test-email', function () {
    return view('emails.send-digital-product');
});

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

Route::post('/login', [LoginController::class, 'login'])->name('login.submit')->middleware('throttle:5,1');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// Google OAuth Routes
Route::get('login/google', [GoogleLoginController::class, 'redirectToGoogle'])->name('google.login');
Route::get('login/google/callback', [GoogleLoginController::class, 'handleGoogleCallback']);

// Halaman Statis
Route::view('/pricing', 'pricing')->name('pricing');
Route::view('/service', 'service')->name('service');
Route::view('/faq', 'faq')->name('FAQ');
Route::view('/about', 'about')->name('about');

// Dashboard (middleware auth jika diperlukan)
Route::get('/dashboard', function () {
    return view('dashboard');
});

// Admin Routes (middleware auth)
Route::middleware(['auth'])->group(function () {

    Route::get('/homeadminS/beranda', [DashboardController::class, 'beranda'])->name('beranda.admins');

    Route::get('/homeadminS/mylinkan', [AdminController::class, 'myLinkan'])->name('mylinkan');

    // Appearance
    Route::get('/homeadminS/appearance', [AppearanceController::class, 'index'])->name('appearance');
    Route::post('/homeadminS/appearance', [AppearanceController::class, 'update'])->name('appearance.update');

    // Settings
    Route::get('/homeadminS/settings', [SettingController::class, 'index'])->name('settings');

    // Account Settings
    Route::get('/homeadminS/account-settings', [AccountController::class, 'edit'])->name('account.settings');
    Route::post('/homeadminS/account-settings/update', [AccountController::class, 'update'])->name('account.update');
    Route::delete('/homeadminS/account-settings/delete', [AccountController::class, 'delete'])->name('account.delete');

    // Payout Routes (Dipindahkan keluar dari grup 'admin' dan disesuaikan dengan URL yang diakses user)
    Route::get('/homeadminS/payout-settings', [PayoutController::class, 'index'])->name('payout.index');
    Route::get('/homeadminS/payout-settings/withdraw', [PayoutController::class, 'showWithdrawForm'])->name('payout.showWithdrawForm');
    Route::post('/homeadminS/payout-settings/withdraw', [PayoutController::class, 'processWithdrawal'])->name('payout.processWithdrawal');
    Route::get('/homeadminS/payout-settings/history', [PayoutController::class, 'showPayoutHistory'])->name('payout.showPayoutHistory');

    // Payout Method Settings
    Route::get('/homeadminS/payout-settings/method', [PayoutController::class, 'showPayoutMethodForm'])->name('payout.showMethodForm');
    Route::post('/homeadminS/payout-settings/method', [PayoutController::class, 'savePayoutMethod'])->name('payout.saveMethod');

    // Statistik
    Route::get('/homeadminS/statistic', [StatisticController::class, 'index'])->name('statistic');
    Route::get('/get-chart-data', [StatisticController::class, 'getChartData'])->name('statistic.chart-data');

    // Orders
    Route::get('/homeadminS/orders', [OrderController::class, 'index'])->name('orders');
    Route::get('/homeadminS/orders/{id}', [OrderController::class, 'getOrderDetail'])->name('orders.detail');

    // Digital Products Resource
    Route::resource('digital-product', DigitalProductController::class);

    Route::get('/homeadminS/mypurchase', [AdminController::class, 'myPurchase'])->name('mypurchase');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'getOrderDetail'])->name('orders.detail');

    Route::prefix('digital-product')->group(function () {
        Route::get('/checkout/{id}', [DigitalProductController::class, 'checkout'])->name('digital-product.checkout');
        Route::post('/store-transaction', [DigitalProductController::class, 'storeTransaction'])->name('digital-product.store-transaction');
        Route::get('/success', [DigitalProductController::class, 'success'])->name('digital-product.success');
        Route::get('/failed', [DigitalProductController::class, 'failed'])->name('digital-product.failed');
        Route::get('/pending', [DigitalProductController::class, 'pending'])->name('digital-product.pending');
        Route::post('/midtrans-callback', [DigitalProductController::class, 'midtransCallback'])->name('digital-product.midtrans-callback');
    });

    Route::get('/homeadminS/shortlink', [ShortlinkController::class, 'index'])->name('shortlink.index');
    Route::post('/homeadminS/shorten', [ShortlinkController::class, 'store'])->name('shortlink.store');
    Route::put('/homeadminS/shortlink/{shortlink}', [ShortlinkController::class, 'update'])->name('shortlink.update');
    Route::get('/homeadminS/shortlink/{shortlink}/analytics', [ShortlinkController::class, 'analytics'])->name('shortlink.analytics');
    Route::get('/homeadminS/shortlink/{shortlink}/analytics/chart', [ShortlinkController::class, 'analyticsChart'])->name('shortlink.analytics.chart');
});

// Route lain yang tidak perlu auth

// Contact Form
Route::get('/contact', [ContactController::class, 'index'])->name('contact.form');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// Forgot Password
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

// Product
Route::get('/product/{id}', [DigitalProductController::class, 'show'])->name('product.show');
Route::post('/cart/update-qty', [DigitalProductController::class, 'updateQty'])->name('cart.updateQty');

// Checkout
Route::match(['get', 'post'], '/checkout/{id}', [DigitalProductController::class, 'checkout'])->name('checkout');

// Password Protected Shortlink
Route::get('/p/{slug}', [ShortlinkController::class, 'passwordForm'])->name('shortlink.password.form');
Route::post('/p/{slug}', [ShortlinkController::class, 'verifyPassword'])->name('shortlink.password.verify');

// Redirect berdasarkan slug (HARUS PALING BAWAH supaya tidak override route lain)
Route::get('/{slug}', [ShortlinkController::class, 'redirect']);

// Platform Admin (middleware auth)
Route::middleware(['auth'])->group(function () {
    Route::get('/admin-platform/beranda', [PlatformAdminController::class, 'beranda'])->name('beranda.platformadmin');

    Route::prefix('platformadmin')->group(function () {
        Route::get('/verifikasi', [VerifikasiController::class, 'index'])->name('verifikasi.platformadmin');
        Route::post('/verifikasi/{id}', [VerifikasiController::class, 'verify'])->name('verifikasi.verify');
        Route::get('/print', [PlatformAdminController::class, 'print'])->name('platformadmin.print');
        Route::post('/print', [PlatformAdminController::class, 'print'])->name('platformadmin.print.post');
    });
});
Route::post('/midtrans/callback', [DigitalProductController::class, 'midtransCallback']);
Route::post('/transaction/store', [DigitalProductController::class, 'storeTransaction'])->name('transaction.store');

// Route untuk verifikasi produk
Route::middleware(['auth', 'role:platform_admin'])->group(function () {
    Route::get('/verification', [VerificationController::class, 'index'])->name('verification.index');
    Route::post('/verification/{id}', [VerificationController::class, 'verify'])->name('verification.verify');
});

// Platform Admin API untuk komisi (realtime)
Route::get('/platformadmin/commissions', [PlatformAdminController::class, 'getCommissions'])->name('platformadmin.commissions');
