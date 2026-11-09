<?php

use App\Http\Controllers\AdminSeller\AccountController;
use App\Http\Controllers\AdminSeller\DashboardController;
use App\Http\Controllers\AdminSeller\DigitalProductController as AdminDigitalProductController;
use App\Http\Controllers\AdminSeller\Microsite\AppearanceController;
use App\Http\Controllers\AdminSeller\Microsite\DigitalProductElementController;
use App\Http\Controllers\AdminSeller\Microsite\DividerElementController;
use App\Http\Controllers\AdminSeller\Microsite\ElementVisibilityController;
use App\Http\Controllers\AdminSeller\Microsite\ImageElementController;
use App\Http\Controllers\AdminSeller\Microsite\SocialMediaElementController;
use App\Http\Controllers\AdminSeller\Microsite\TextElementController;
use App\Http\Controllers\AdminSeller\Microsite\VideoElementController;
use App\Http\Controllers\AdminSeller\OrderController;
use App\Http\Controllers\AdminSeller\PayoutController;
use App\Http\Controllers\AdminSeller\SettingController;
use App\Http\Controllers\AdminSeller\ShortlinkController as AdminShortlinkController;
use App\Http\Controllers\AdminSeller\StatisticController;
use App\Http\Controllers\AdminSeller\SupportTicketController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MicrositeController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    // Dashboard / Beranda
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/get-chart-data', [DashboardController::class, 'getChartData'])->name('chart-data');
    Route::get('/notifications', [DashboardController::class, 'getNotifications'])->name('notifications');
    Route::post('/notifications/read', [DashboardController::class, 'markNotificationRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [DashboardController::class, 'markAllNotificationsRead'])->name('notifications.read-all');
    Route::get('/notifications/stream', [DashboardController::class, 'streamNotifications'])->name('notifications.stream');
    Route::post('/appeal', [DashboardController::class, 'submitAppeal'])->name('appeal.store');

    // My Linkan (microsite builder)
    Route::get('/microsites', [MicrositeController::class, 'index'])->name('microsites.index');
    Route::post('/microsites', [MicrositeController::class, 'store'])->name('microsites.store');
    Route::delete('/microsites/{id}', [MicrositeController::class, 'destroy'])->name('microsites.destroy');

    // Appearance (Profile Block settings)
    Route::post('/appearance', [AppearanceController::class, 'update'])->name('appearance.update');
    Route::post('/appearance/design-settings', [AppearanceController::class, 'updateDesignSettings'])->name('appearance.design-settings.update');

    // Microsite Elements
    Route::post('/elements/image', [ImageElementController::class, 'store'])->name('elements.image.store');
    Route::delete('/elements/image/{id}', [ImageElementController::class, 'destroy'])->name('elements.image.destroy');

    Route::post('/elements/text', [TextElementController::class, 'store'])->name('elements.text.store');
    Route::delete('/elements/text/{id}', [TextElementController::class, 'destroy'])->name('elements.text.destroy');

    Route::post('/elements/divider', [DividerElementController::class, 'store'])->name('elements.divider.store');
    Route::delete('/elements/divider/{id}', [DividerElementController::class, 'destroy'])->name('elements.divider.destroy');

    Route::post('/elements/video', [VideoElementController::class, 'store'])->name('elements.video.store');
    Route::put('/elements/video/{id}', [VideoElementController::class, 'update'])->name('elements.video.update');
    Route::delete('/elements/video/{id}', [VideoElementController::class, 'destroy'])->name('elements.video.destroy');

    Route::post('/elements/social', [SocialMediaElementController::class, 'store'])->name('elements.social.store');
    Route::put('/elements/social/{id}', [SocialMediaElementController::class, 'update'])->name('elements.social.update');
    Route::delete('/elements/social/{id}', [SocialMediaElementController::class, 'destroy'])->name('elements.social.destroy');

    Route::post('/elements/digital-product/pin', [DigitalProductElementController::class, 'pinExisting'])->name('elements.digital-product.pin');
    Route::post('/elements/digital-product', [DigitalProductElementController::class, 'store'])->name('elements.digital-product.store');
    Route::delete('/elements/digital-product/{id}', [DigitalProductElementController::class, 'destroy'])->name('elements.digital-product.destroy');

    Route::post('/elements/toggle-visibility', [ElementVisibilityController::class, 'toggle'])->name('elements.toggleVisibility');
    Route::post('/elements/order', [AppearanceController::class, 'updateOrder'])->name('elements.order.update');

    // Settings (general)
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');

    // Account
    Route::get('/account', [AccountController::class, 'edit'])->name('account');
    Route::post('/account', [AccountController::class, 'update'])->name('account.update');
    Route::post('/account/password', [AccountController::class, 'updatePassword'])->name('account.password');
    Route::post('/account/notifications', [AccountController::class, 'updateNotifications'])->name('account.notifications');
    Route::post('/account/email/request-otp', [AccountController::class, 'requestEmailChangeOtp'])->name('account.email.request-otp');
    Route::post('/account/email/verify-otp', [AccountController::class, 'verifyEmailChangeOtp'])->name('account.email.verify-otp');
    Route::get('/account/email/verify/{token}', [AccountController::class, 'verifyEmailChange'])->name('account.email.verify');
    Route::delete('/account/google', [AccountController::class, 'disconnectGoogle'])->name('account.google.disconnect');
    Route::delete('/account/sessions/{sessionId}', [AccountController::class, 'revokeSession'])->name('account.session.revoke');
    Route::delete('/account/sessions', [AccountController::class, 'revokeAllSessions'])->name('account.sessions.revoke-all');
    Route::delete('/account', [AccountController::class, 'delete'])->middleware('throttle:5,1')->name('account.delete');

    // Payout
    Route::prefix('payout')->name('payout.')->group(function () {
        Route::get('/', [PayoutController::class, 'index'])->name('index');
        Route::get('/withdraw', [PayoutController::class, 'showWithdrawForm'])->name('withdraw');
        Route::post('/withdraw', [PayoutController::class, 'processWithdrawal'])->middleware('throttle:6,1')->name('withdraw.process');
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

    // Digital Products Seller CRUD
    Route::resource('digital-products', AdminDigitalProductController::class);

    // Digital product payment flow (initiated from admin context)
    Route::prefix('digital-products')->name('digital-products.')->group(function () {
        Route::get('/checkout/{id}', [CheckoutController::class, 'checkout'])->name('checkout');
        Route::post('/transaction', [CheckoutController::class, 'storeTransaction'])->name('transaction');
    });

    // Shortlinks (Seller CRUD & Analytics)
    Route::prefix('shortlinks')->name('shortlinks.')->group(function () {
        Route::get('/', [AdminShortlinkController::class, 'index'])->name('index');
        Route::post('/', [AdminShortlinkController::class, 'store'])->name('store');
        Route::put('/{shortlink}', [AdminShortlinkController::class, 'update'])->name('update');
        Route::get('/{shortlink}/analytics', [AdminShortlinkController::class, 'analytics'])->name('analytics');
        Route::get('/{shortlink}/analytics/chart', [AdminShortlinkController::class, 'analyticsChart'])->name('analytics.chart');
    });

    // Support Tickets (Seller Helpdesk)
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', [SupportTicketController::class, 'index'])->name('index');
        Route::post('/', [SupportTicketController::class, 'store'])->name('store');
        Route::get('/{id}', [SupportTicketController::class, 'show'])->name('show');
        Route::post('/{id}/reply', [SupportTicketController::class, 'reply'])->name('reply');
    });
});
