<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Services\AdminSeller\DashboardService;

class SellerLayoutComposer
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $user = Auth::user();

        if (!$user) {
            $view->with([
                'sellerName' => 'User',
                'sellerInitials' => 'US',
                'sellerAvatar' => null,
                'sellerBalance' => 0,
                'sellerNotifications' => collect(),
                'sellerUnreadCount' => 0,
                'sellerHasUnread' => false,
                'sellerDisplayCount' => '0',
                'sellerHeaderCount' => '00',
            ]);
            return;
        }

        // Cache balance calculation for 30 seconds to prevent query on every single request
        $balance = Cache::remember("seller_balance_{$user->id}", 30, function () use ($user) {
            return (float) DB::table('transactions')
                ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
                ->where('digital_products.user_id', $user->id)
                ->where('transactions.status', 'success')
                ->sum('transactions.total_price');
        });

        // Fetch notifications safely
        $notifData = $this->dashboardService->fetchSellerNotificationsData($user);
        $notifications = collect($notifData['notifications'] ?? [])->take(5);
        $unreadCount = (int) ($notifData['unread_count'] ?? 0);
        $hasUnread = $unreadCount > 0;
        $displayCount = $unreadCount > 99 ? '99+' : (string) $unreadCount;
        $headerCount = str_pad((string) $unreadCount, 2, '0', STR_PAD_LEFT);

        $name = $user->username ?? $user->name ?? 'User';
        $initials = strtoupper(substr($name, 0, 2));

        $view->with([
            'sellerUser' => $user,
            'sellerName' => $name,
            'sellerInitials' => $initials,
            'sellerAvatar' => $user->avatar,
            'sellerBalance' => $balance,
            'sellerNotifications' => $notifications,
            'sellerUnreadCount' => $unreadCount,
            'sellerHasUnread' => $hasUnread,
            'sellerDisplayCount' => $displayCount,
            'sellerHeaderCount' => $headerCount,
        ]);
    }
}
