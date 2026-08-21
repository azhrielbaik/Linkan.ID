<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppearanceController;
use App\Http\Controllers\ImageElementController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DigitalProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PayoutController;
use App\Http\Controllers\PlatformAdmin\VerifikasiController;
use App\Http\Controllers\PlatformAdminController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ShortlinkController;
use App\Http\Controllers\StatisticController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => view('welcome'))->name('welcome');

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

// Static pages
Route::view('/pricing', 'pricing')->name('pricing');
Route::view('/service', 'service')->name('service');
Route::view('/faq', 'faq')->name('FAQ');
Route::view('/about', 'about')->name('about');

// Auth
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// Google OAuth
Route::get('login/google', [LoginController::class, 'redirectToGoogle'])->name('google.login');
Route::get('login/google/callback', [LoginController::class, 'handleGoogleCallback'])->name('google.callback');

// Password Reset
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

// Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact.form');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// Public microsite & link tracking
Route::get('/linkan.id/{username}', [PublicPageController::class, 'show'])->name('track.view');
Route::get('/track-click', [DashboardController::class, 'trackClick'])->name('track.click');
Route::get('/profile/{username}', [PublicPageController::class, 'show'])->name('public.profile');

// Public product & checkout (no auth required to browse/buy)
Route::get('/product/{id}', [DigitalProductController::class, 'show'])->name('product.show');
Route::match(['get', 'post'], '/checkout/{id}', [DigitalProductController::class, 'checkout'])->name('checkout');
Route::post('/cart/update-qty', [DigitalProductController::class, 'updateQty'])->name('cart.updateQty');

// Digital product payment flow (public callbacks & result pages)
Route::post('/midtrans/callback', [DigitalProductController::class, 'midtransCallback'])->name('midtrans.callback');
Route::post('/transaction/store', [DigitalProductController::class, 'storeTransaction'])->name('transaction.store');

// Password-protected shortlink
Route::get('/p/{slug}', [ShortlinkController::class, 'passwordForm'])->name('shortlink.password.form');
Route::post('/p/{slug}', [ShortlinkController::class, 'verifyPassword'])->name('shortlink.password.verify');

/*
|--------------------------------------------------------------------------
| User Admin Routes — prefix: /admin
|--------------------------------------------------------------------------
| Semua halaman dashboard user (seller) dikelompokkan di sini.
| Naming convention: admin.<resource>.<action>
*/

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    // Dashboard / Beranda
    Route::get('/dashboard', [DashboardController::class, 'beranda'])->name('dashboard');
    Route::get('/get-chart-data', [DashboardController::class, 'getChartData'])->name('chart-data');
    Route::post('/appeal', [DashboardController::class, 'submitAppeal'])->name('appeal.store');

    // My Linkan (microsite builder)
    Route::get('/mylinkan', [AdminController::class, 'myLinkan'])->name('mylinkan');
    Route::post('/microsite/create', [AdminController::class, 'storeMicrosite'])->name('microsite.store');

    // Appearance
    Route::get('/appearance', [AppearanceController::class, 'index'])->name('appearance');
    Route::post('/appearance', [AppearanceController::class, 'update'])->name('appearance.update');

    // Microsite Elements
    Route::post('/elements/image', [ImageElementController::class, 'store'])->name('elements.image.store');
    Route::delete('/elements/image/{id}', [ImageElementController::class, 'destroy'])->name('elements.image.destroy');
    Route::post('/elements/order', [ImageElementController::class, 'updateOrder'])->name('elements.order.update');

    // Settings (general)
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');

    // Account
    Route::get('/account', [AccountController::class, 'edit'])->name('account');
    Route::post('/account', [AccountController::class, 'update'])->name('account.update');
    Route::delete('/account', [AccountController::class, 'delete'])->name('account.delete');

    // Payout
    Route::prefix('payout')->name('payout.')->group(function () {
        Route::get('/', [PayoutController::class, 'index'])->name('index');
        Route::get('/withdraw', [PayoutController::class, 'showWithdrawForm'])->name('withdraw');
        Route::post('/withdraw', [PayoutController::class, 'processWithdrawal'])->name('withdraw.process');
        Route::get('/history', [PayoutController::class, 'showPayoutHistory'])->name('history');
        Route::get('/method', [PayoutController::class, 'showPayoutMethodForm'])->name('method');
        Route::post('/method', [PayoutController::class, 'savePayoutMethod'])->name('method.save');
    });

    // Statistics
    Route::get('/statistics', [StatisticController::class, 'index'])->name('statistics');
    Route::get('/statistics/chart-data', [StatisticController::class, 'getChartData'])->name('statistics.chart-data');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::get('/orders/{id}', [OrderController::class, 'getOrderDetail'])->name('orders.detail');

    // Digital Products (CRUD resource)
    Route::resource('digital-products', DigitalProductController::class)->names([
        'index'   => 'digital-products.index',
        'create'  => 'digital-products.create',
        'store'   => 'digital-products.store',
        'show'    => 'digital-products.show',
        'edit'    => 'digital-products.edit',
        'update'  => 'digital-products.update',
        'destroy' => 'digital-products.destroy',
    ]);

    // Digital product payment flow (initiated from admin context)
    Route::prefix('digital-products')->name('digital-products.')->group(function () {
        Route::get('/checkout/{id}', [DigitalProductController::class, 'checkout'])->name('checkout');
        Route::post('/transaction', [DigitalProductController::class, 'storeTransaction'])->name('transaction');
        Route::get('/success', [DigitalProductController::class, 'success'])->name('success');
        Route::get('/failed', [DigitalProductController::class, 'failed'])->name('failed');
        Route::get('/pending', [DigitalProductController::class, 'pending'])->name('pending');
        Route::post('/midtrans-callback', [DigitalProductController::class, 'midtransCallback'])->name('midtrans-callback');
    });

    // My Purchases
    Route::get('/purchases', [AdminController::class, 'myPurchase'])->name('purchases');

    // Shortlinks
    Route::prefix('shortlinks')->name('shortlinks.')->group(function () {
        Route::get('/', [ShortlinkController::class, 'index'])->name('index');
        Route::post('/', [ShortlinkController::class, 'store'])->name('store');
        Route::put('/{shortlink}', [ShortlinkController::class, 'update'])->name('update');
        Route::get('/{shortlink}/analytics', [ShortlinkController::class, 'analytics'])->name('analytics');
        Route::get('/{shortlink}/analytics/chart', [ShortlinkController::class, 'analyticsChart'])->name('analytics.chart');
    });
});

/*
|--------------------------------------------------------------------------
| Platform Admin Routes — prefix: /platform-admin
|--------------------------------------------------------------------------
| Semua halaman back-office platform admin dikelompokkan di sini.
| Naming convention: platform-admin.<resource>.<action>
*/

Route::prefix('platform-admin')->name('platform-admin.')->middleware(['auth', 'role:admin_platform'])->group(function () {

    Route::get('/dashboard', [PlatformAdminController::class, 'beranda'])->name('dashboard');

    // Verifikasi produk
    Route::get('/verifikasi', [VerifikasiController::class, 'index'])->name('verifikasi');
    Route::post('/verifikasi/{id}', [VerifikasiController::class, 'verify'])->name('verifikasi.verify');

    // Print / laporan & Export
    Route::match(['get', 'post'], '/print', [PlatformAdminController::class, 'print'])->name('print');
    Route::get('/export/excel', [PlatformAdminController::class, 'exportExcel'])->name('export.excel');

    // Komisi (API endpoint realtime)
    Route::get('/commissions', [PlatformAdminController::class, 'getCommissions'])->name('commissions');

    // Verifikasi (role-gated, sudah dalam group ini)
    Route::get('/verification', [VerificationController::class, 'index'])->name('verification');
    Route::post('/verification/{id}', [VerificationController::class, 'verify'])->name('verification.verify');

    // Manajemen User & Banding Suspend
    Route::get('/users', [PlatformAdminController::class, 'users'])->name('users');
    Route::get('/users/appeals', [PlatformAdminController::class, 'appeals'])->name('users.appeals');
    Route::get('/users/{id}/detail', [PlatformAdminController::class, 'sellerDetail'])->name('users.detail');
    Route::post('/users/{id}/suspend', [PlatformAdminController::class, 'suspend'])->name('users.suspend');
    Route::post('/users/{id}/activate', [PlatformAdminController::class, 'activate'])->name('users.activate');
    Route::post('/users/appeals/{id}/approve', [PlatformAdminController::class, 'approveAppeal'])->name('users.appeals.approve');
    Route::post('/users/appeals/{id}/reject', [PlatformAdminController::class, 'rejectAppeal'])->name('users.appeals.reject');

    // Manajemen Payout (Request Withdraw & Riwayat Global)
    Route::get('/payouts', [\App\Http\Controllers\PlatformAdmin\PayoutManagementController::class, 'index'])->name('payouts.index');
    Route::post('/payouts/{id}/approve', [\App\Http\Controllers\PlatformAdmin\PayoutManagementController::class, 'approve'])->name('payouts.approve');
    Route::post('/payouts/{id}/reject', [\App\Http\Controllers\PlatformAdmin\PayoutManagementController::class, 'reject'])->name('payouts.reject');

    // Manajemen Produk (Semua Produk, Takedown, Restore)
    Route::get('/products', [\App\Http\Controllers\PlatformAdmin\ProductManagementController::class, 'index'])->name('products.index');
    Route::post('/products/{id}/takedown', [\App\Http\Controllers\PlatformAdmin\ProductManagementController::class, 'takedown'])->name('products.takedown');
    Route::post('/products/{id}/restore', [\App\Http\Controllers\PlatformAdmin\ProductManagementController::class, 'restore'])->name('products.restore');

    // Log & Audit
    Route::get('/logs/activity', [\App\Http\Controllers\PlatformAdmin\LogController::class, 'activityLogs'])->name('logs.activity');
    Route::get('/logs/transactions', [\App\Http\Controllers\PlatformAdmin\LogController::class, 'transactionLogs'])->name('logs.transactions');

    // Pengaturan Platform & Broadcast
    Route::get('/settings', [\App\Http\Controllers\PlatformAdmin\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\PlatformAdmin\SettingController::class, 'updateSettings'])->name('settings.update');
    Route::post('/settings/broadcast', [\App\Http\Controllers\PlatformAdmin\SettingController::class, 'storeBroadcast'])->name('settings.broadcast.store');
    Route::post('/settings/broadcast/{id}/toggle', [\App\Http\Controllers\PlatformAdmin\SettingController::class, 'toggleBroadcast'])->name('settings.broadcast.toggle');
    Route::delete('/settings/broadcast/{id}', [\App\Http\Controllers\PlatformAdmin\SettingController::class, 'deleteBroadcast'])->name('settings.broadcast.delete');
});


/*
|--------------------------------------------------------------------------
| Dev / Debug Routes (remove in production)
|--------------------------------------------------------------------------
*/
Route::get('/test-email', fn () => view('emails.send-digital-product'));

/*
|--------------------------------------------------------------------------
| Slug Redirect — MUST remain last
|--------------------------------------------------------------------------
*/
Route::get('/{slug}', [ShortlinkController::class, 'redirect'])->name('shortlink.redirect');
