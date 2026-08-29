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
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:10,1')->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// Google OAuth
Route::get('login/google', [LoginController::class, 'redirectToGoogle'])->name('google.login');
Route::get('login/google/callback', [LoginController::class, 'handleGoogleCallback'])->name('google.callback');

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

// Legacy route fallback
Route::get('/reset-password-otp', [ForgotPasswordController::class, 'showVerifyOtpForm'])->name('password.otp');
Route::post('/reset-password-otp/submit', [ForgotPasswordController::class, 'verifyOtp'])->name('password.otp.submit');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'submitCreatePassword'])->name('password.update');

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
Route::post('/midtrans/callback', [DigitalProductController::class, 'midtransCallback'])->middleware('throttle:30,1')->name('midtrans.callback');
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
    Route::get('/notifications', [DashboardController::class, 'getNotifications'])->name('notifications');
    Route::get('/notifications/stream', [DashboardController::class, 'streamNotifications'])->name('notifications.stream');
    Route::post('/appeal', [DashboardController::class, 'submitAppeal'])->name('appeal.store');

    // My Linkan (microsite builder)
    Route::get('/mylinkan', [AdminController::class, 'myLinkan'])->name('mylinkan');
    Route::post('/microsite/create', [AdminController::class, 'storeMicrosite'])->name('microsite.store');

    // Appearance
    Route::get('/appearance', [AppearanceController::class, 'index'])->name('appearance');
    Route::post('/appearance', [AppearanceController::class, 'update'])->name('appearance.update');
    Route::post('/appearance/design-settings', [AppearanceController::class, 'updateDesignSettings'])->name('appearance.design-settings.update');

    // Microsite Elements
    Route::post('/elements/image', [\App\Http\Controllers\ImageElementController::class, 'store'])->name('elements.image.store');
    Route::delete('/elements/image/{id}', [\App\Http\Controllers\ImageElementController::class, 'destroy'])->name('elements.image.destroy');

    // Text Element Routes
    Route::post('/elements/text', [\App\Http\Controllers\TextElementController::class, 'store'])->name('elements.text.store');
    Route::delete('/elements/text/{id}', [\App\Http\Controllers\TextElementController::class, 'destroy'])->name('elements.text.destroy');

    // Divider Element Routes
    Route::post('/elements/divider', [\App\Http\Controllers\DividerElementController::class, 'store'])->name('elements.divider.store');
    Route::delete('/elements/divider/{id}', [\App\Http\Controllers\DividerElementController::class, 'destroy'])->name('elements.divider.destroy');

    // Video Element
    Route::post('/elements/video', [\App\Http\Controllers\VideoElementController::class, 'store'])->name('elements.video.store');
    Route::put('/elements/video/{id}', [\App\Http\Controllers\VideoElementController::class, 'update'])->name('elements.video.update');
    Route::delete('/elements/video/{id}', [\App\Http\Controllers\VideoElementController::class, 'destroy'])->name('elements.video.destroy');

    // Social Media Element
    Route::post('/elements/social', [\App\Http\Controllers\SocialMediaElementController::class, 'store'])->name('elements.social.store');
    Route::put('/elements/social/{id}', [\App\Http\Controllers\SocialMediaElementController::class, 'update'])->name('elements.social.update');
    Route::delete('/elements/social/{id}', [\App\Http\Controllers\SocialMediaElementController::class, 'destroy'])->name('elements.social.destroy');

    // Visibility Toggle Route
    Route::post('/elements/toggle-visibility', [\App\Http\Controllers\ElementVisibilityController::class, 'toggle'])->name('elements.toggleVisibility');

    Route::post('/elements/order', [\App\Http\Controllers\ImageElementController::class, 'updateOrder'])->name('elements.order.update');

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

    // Support Tickets (Seller Helpdesk)
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SupportTicketController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\SupportTicketController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\SupportTicketController::class, 'show'])->name('show');
        Route::post('/{id}/reply', [\App\Http\Controllers\SupportTicketController::class, 'reply'])->name('reply');
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
    Route::post('/verifikasi/bulk', [VerifikasiController::class, 'bulkVerify'])->name('verifikasi.bulk');
    Route::post('/verifikasi/{id}', [VerifikasiController::class, 'verify'])->name('verifikasi.verify');

    // Print / laporan & Export
    Route::match(['get', 'post'], '/print', [PlatformAdminController::class, 'print'])->name('print');
    Route::get('/export/excel', [PlatformAdminController::class, 'exportExcel'])->name('export.excel');

    // Komisi & Notifikasi (API endpoint realtime & SSE stream)
    Route::get('/commissions', [PlatformAdminController::class, 'getCommissions'])->name('commissions');
    Route::get('/notifications', [PlatformAdminController::class, 'getNotifications'])->name('notifications');
    Route::get('/notifications/stream', [PlatformAdminController::class, 'streamNotifications'])->name('notifications.stream');

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

    // Pusat Bantuan / Support Tickets (Platform Admin)
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', [\App\Http\Controllers\PlatformAdmin\SupportTicketManagementController::class, 'index'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\PlatformAdmin\SupportTicketManagementController::class, 'show'])->name('show');
        Route::post('/{id}/reply', [\App\Http\Controllers\PlatformAdmin\SupportTicketManagementController::class, 'reply'])->name('reply');
        Route::post('/{id}/status', [\App\Http\Controllers\PlatformAdmin\SupportTicketManagementController::class, 'updateStatus'])->name('status');
    });

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

    // Theme & Tampilan Platform Admin
    Route::post('/theme', [\App\Http\Controllers\PlatformAdmin\ThemeController::class, 'update'])->name('theme.update');
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
