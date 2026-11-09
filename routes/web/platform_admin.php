<?php

use App\Http\Controllers\PlatformAdmin\AdminProfileController;
use App\Http\Controllers\PlatformAdmin\DashboardController;
use App\Http\Controllers\PlatformAdmin\DisputeManagementController;
use App\Http\Controllers\PlatformAdmin\LogController;
use App\Http\Controllers\PlatformAdmin\PayoutManagementController;
use App\Http\Controllers\PlatformAdmin\ProductManagementController;
use App\Http\Controllers\PlatformAdmin\ReportExportController;
use App\Http\Controllers\PlatformAdmin\SettingController;
use App\Http\Controllers\PlatformAdmin\SupportTicketManagementController;
use App\Http\Controllers\PlatformAdmin\UserManagementController;
use App\Http\Controllers\PlatformAdmin\VerifikasiController;
use Illuminate\Support\Facades\Route;

Route::prefix('platform-admin')->name('platform-admin.')->middleware(['auth', 'role:admin_platform', 'admin.timeout'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'beranda'])->name('dashboard');

    // Verifikasi produk
    Route::get('/verifikasi', [VerifikasiController::class, 'index'])->name('verifikasi');
    Route::post('/verifikasi/bulk', [VerifikasiController::class, 'bulkVerify'])->middleware('throttle:30,1')->name('verifikasi.bulk');
    Route::post('/verifikasi/{id}', [VerifikasiController::class, 'verify'])->middleware('throttle:30,1')->name('verifikasi.verify');

    // Print / laporan & Export
    Route::match(['get', 'post'], '/print', [ReportExportController::class, 'print'])->name('print');
    Route::get('/export/excel', [ReportExportController::class, 'exportExcel'])->name('export.excel');

    // Komisi & Notifikasi (API endpoint realtime & SSE stream)
    Route::get('/commissions', [DashboardController::class, 'getCommissions'])->name('commissions');
    Route::get('/notifications', [DashboardController::class, 'getNotifications'])->name('notifications');
    Route::post('/notifications/read', [DashboardController::class, 'markNotificationRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [DashboardController::class, 'markAllNotificationsRead'])->name('notifications.read-all');
    Route::get('/notifications/stream', [DashboardController::class, 'streamNotifications'])->name('notifications.stream');

    // Manajemen User & Banding Suspend
    Route::get('/users', [UserManagementController::class, 'users'])->name('users');
    Route::get('/users/suggest', [UserManagementController::class, 'userSuggest'])->middleware('throttle:60,1')->name('users.suggest');
    Route::get('/users/appeals', [UserManagementController::class, 'appeals'])->name('users.appeals');
    Route::get('/users/{id}/detail', [UserManagementController::class, 'sellerDetail'])->name('users.detail');
    Route::post('/users/{id}/suspend', [UserManagementController::class, 'suspend'])->middleware('throttle:15,1')->name('users.suspend');
    Route::post('/users/{id}/activate', [UserManagementController::class, 'activate'])->middleware('throttle:15,1')->name('users.activate');
    Route::post('/users/appeals/{id}/approve', [UserManagementController::class, 'approveAppeal'])->middleware('throttle:15,1')->name('users.appeals.approve');
    Route::post('/users/appeals/{id}/reject', [UserManagementController::class, 'rejectAppeal'])->middleware('throttle:15,1')->name('users.appeals.reject');

    // Pusat Bantuan / Support Tickets (Platform Admin)
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', [SupportTicketManagementController::class, 'index'])->name('index');
        Route::get('/{id}', [SupportTicketManagementController::class, 'show'])->name('show');
        Route::post('/{id}/reply', [SupportTicketManagementController::class, 'reply'])->middleware('throttle:20,1')->name('reply');
        Route::post('/{id}/status', [SupportTicketManagementController::class, 'updateStatus'])->middleware('throttle:20,1')->name('status');
    });

    // Manajemen Payout
    Route::get('/payouts', [PayoutManagementController::class, 'index'])->name('payouts.index');
    Route::post('/payouts/{id}/approve', [PayoutManagementController::class, 'approve'])->middleware('throttle:15,1')->name('payouts.approve');
    Route::post('/payouts/{id}/reject', [PayoutManagementController::class, 'reject'])->middleware('throttle:15,1')->name('payouts.reject');

    // Manajemen Produk
    Route::get('/products', [ProductManagementController::class, 'index'])->name('products.index');
    Route::post('/products/{id}/takedown', [ProductManagementController::class, 'takedown'])->middleware('throttle:20,1')->name('products.takedown');
    Route::post('/products/{id}/restore', [ProductManagementController::class, 'restore'])->middleware('throttle:20,1')->name('products.restore');

    // Log & Audit
    Route::get('/logs/activity', [LogController::class, 'activityLogs'])->name('logs.activity');
    Route::get('/logs/activity/export-archive', [LogController::class, 'exportArchive'])->name('logs.activity.export-archive');
    Route::get('/logs/activity/suggest', [LogController::class, 'activitySuggest'])->middleware('throttle:60,1')->name('logs.activity.suggest');
    Route::get('/logs/transactions', [LogController::class, 'transactionLogs'])->name('logs.transactions');
    Route::get('/logs/transactions/suggest', [LogController::class, 'transactionSuggest'])->middleware('throttle:60,1')->name('logs.transactions.suggest');

    // Pengaturan Platform & Broadcast
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'updateSettings'])->middleware('throttle:10,1')->name('settings.update');
    Route::post('/settings/emergency-switches', [SettingController::class, 'updateEmergencySwitches'])->middleware('throttle:10,1')->name('settings.emergency');
    Route::post('/settings/broadcast', [SettingController::class, 'storeBroadcast'])->middleware('throttle:15,1')->name('settings.broadcast.store');
    Route::post('/settings/broadcast/{id}/toggle', [SettingController::class, 'toggleBroadcast'])->middleware('throttle:20,1')->name('settings.broadcast.toggle');
    Route::delete('/settings/broadcast/{id}', [SettingController::class, 'deleteBroadcast'])->middleware('throttle:15,1')->name('settings.broadcast.delete');

    // Profil, Password, Avatar & Sesi
    Route::post('/password/update', [AdminProfileController::class, 'updatePassword'])->middleware('throttle:10,1')->name('password.update');
    Route::post('/profile/update', [AdminProfileController::class, 'updateProfile'])->middleware('throttle:15,1')->name('profile.update');
    Route::delete('/profile/avatar', [AdminProfileController::class, 'deleteAvatar'])->middleware('throttle:15,1')->name('profile.avatar.delete');
    Route::get('/sessions', [AdminProfileController::class, 'getActiveSessions'])->name('sessions.index');
    Route::post('/sessions/revoke-others', [AdminProfileController::class, 'revokeOtherSessions'])->middleware('throttle:10,1')->name('sessions.revoke-others');

    // Manajemen Sengketa & Refund Pembeli
    Route::get('/disputes', [DisputeManagementController::class, 'index'])->name('disputes.index');
    Route::get('/disputes/{id}', [DisputeManagementController::class, 'show'])->name('disputes.show');
    Route::post('/disputes/{id}/review', [DisputeManagementController::class, 'startReview'])->middleware('throttle:15,1')->name('disputes.review');
    Route::post('/disputes/{id}/refund', [DisputeManagementController::class, 'processRefund'])->middleware('throttle:10,1')->name('disputes.refund');
    Route::post('/disputes/{id}/reject', [DisputeManagementController::class, 'rejectDispute'])->middleware('throttle:10,1')->name('disputes.reject');
});
