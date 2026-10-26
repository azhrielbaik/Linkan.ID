<?php

namespace App\Services\PlatformAdmin;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PlatformNotificationService
{
    /**
     * Ambil jumlah tiket yang open dengan caching (TTL: 60 detik).
     */
    public function getPendingTicketsCount(): int
    {
        return Cache::remember('platform_admin_pending_tickets_count', 60, function () {
            return (int) DB::table('support_tickets')->where('status', 'open')->count();
        });
    }

    /**
     * Hapus cache data tiket platform saat status tiket berubah.
     */
    public function clearPendingTicketsCache(): void
    {
        Cache::forget('platform_admin_pending_tickets_count');
    }

    /**
     * Hapus cache data notifikasi platform saat ada pembaruan status data.
     */
    public function clearNotificationsCache(): void
    {
        Cache::forget('platform_admin_raw_notifications');
        Cache::forget('platform_admin_dashboard_stats');
    }

    /**
     * Ambil data notifikasi mentah dari database (atau dari cache berdurasi 10 detik).
     */
    public function getCachedRawNotifications(): array
    {
        return Cache::remember('platform_admin_raw_notifications', 10, function () {
            $notifications = [];

            // 1. Pending Digital Products
            $pendingProducts = DB::table('digital_products')
                ->join('users', 'digital_products.user_id', '=', 'users.id')
                ->where('digital_products.verification_status', 'pending')
                ->select(
                    'digital_products.id',
                    'digital_products.title as product_name',
                    'digital_products.created_at',
                    'users.name as seller_name',
                    'users.email as seller_email'
                )
                ->orderBy('digital_products.created_at', 'desc')
                ->limit(10)
                ->get();

            foreach ($pendingProducts as $prod) {
                $notifId = 'prod_'.$prod->id;
                $notifications[] = [
                    'id' => $notifId,
                    'type' => 'product',
                    'title' => 'Verifikasi Produk Baru',
                    'seller_name' => $prod->seller_name,
                    'product_name' => $prod->product_name,
                    'url' => route('platform-admin.verifikasi'),
                    'time_ago' => Carbon::parse($prod->created_at)->diffForHumans(),
                    'timestamp' => strtotime($prod->created_at),
                ];
            }

            // 2. Pending Payout Requests
            $pendingPayouts = DB::table('payout_transactions')
                ->join('users', 'payout_transactions.user_id', '=', 'users.id')
                ->where('payout_transactions.status', 'pending')
                ->select(
                    'payout_transactions.id',
                    'payout_transactions.amount',
                    'payout_transactions.method',
                    'payout_transactions.bank_name',
                    'payout_transactions.created_at',
                    'users.name as seller_name'
                )
                ->orderBy('payout_transactions.created_at', 'desc')
                ->limit(10)
                ->get();

            foreach ($pendingPayouts as $payout) {
                $notifId = 'payout_'.$payout->id;
                $notifications[] = [
                    'id' => $notifId,
                    'type' => 'payout',
                    'title' => 'Permintaan Payout Baru',
                    'seller_name' => $payout->seller_name,
                    'amount' => number_format($payout->amount, 0, ',', '.'),
                    'bank' => $payout->bank_name ?? strtoupper($payout->method),
                    'url' => route('platform-admin.payouts.index'),
                    'time_ago' => Carbon::parse($payout->created_at)->diffForHumans(),
                    'timestamp' => strtotime($payout->created_at),
                ];
            }

            // 3. Pending Suspension Appeals
            $pendingAppeals = DB::table('suspension_appeals')
                ->join('users', 'suspension_appeals.user_id', '=', 'users.id')
                ->where('suspension_appeals.status', 'pending')
                ->select(
                    'suspension_appeals.id',
                    'suspension_appeals.appeal_reason',
                    'suspension_appeals.created_at',
                    'users.name as seller_name'
                )
                ->orderBy('suspension_appeals.created_at', 'desc')
                ->limit(10)
                ->get();

            foreach ($pendingAppeals as $appeal) {
                $notifId = 'appeal_'.$appeal->id;
                $notifications[] = [
                    'id' => $notifId,
                    'type' => 'appeal',
                    'title' => 'Permohonan Banding Akun',
                    'seller_name' => $appeal->seller_name,
                    'url' => route('platform-admin.users', ['view' => 'appeals']),
                    'time_ago' => Carbon::parse($appeal->created_at)->diffForHumans(),
                    'timestamp' => strtotime($appeal->created_at),
                ];
            }

            // 4. Peringatan Pembersihan Log (H-1) jika ada log berumur >= 29 hari
            $expiringLogsCount = DB::table('activity_logs')
                ->where('created_at', '<=', Carbon::now()->subDays(29))
                ->count();

            if ($expiringLogsCount > 0) {
                $logNotifId = 'log_cleanup_warning';
                $notifications[] = [
                    'id' => $logNotifId,
                    'type' => 'log_cleanup',
                    'title' => 'Pembersihan Log (H-1)',
                    'seller_name' => 'Sistem Platform',
                    'url' => route('platform-admin.logs.activity.export-archive'),
                    'time_ago' => 'Besok 02:00',
                    'timestamp' => time(),
                    'message' => "{$expiringLogsCount} log (> 29 hari) akan dibersihkan otomatis besok pkl 02:00. Klik untuk mengunduh cadangan CSV.",
                ];
            }

            usort($notifications, fn ($a, $b) => $b['timestamp'] - $a['timestamp']);

            return [
                'items' => $notifications,
                'counts' => [
                    'products' => count($pendingProducts),
                    'payouts' => count($pendingPayouts),
                    'appeals' => count($pendingAppeals),
                    'log_cleanup' => $expiringLogsCount > 0 ? 1 : 0,
                ],
            ];
        });
    }

    /**
     * Fetch formatted notifications data with user read state
     */
    public function getNotificationsData(array $readKeys = []): array
    {
        $cached = $this->getCachedRawNotifications();
        $items = $cached['items'] ?? [];

        $notifications = array_map(function ($item) use ($readKeys) {
            $item['is_read'] = in_array($item['id'], $readKeys, true);

            return $item;
        }, $items);

        $unreadCount = count(array_filter($notifications, fn ($n) => ! $n['is_read']));

        return [
            'status' => 'success',
            'unread_count' => $unreadCount,
            'counts' => $cached['counts'] ?? [
                'products' => 0,
                'payouts' => 0,
                'appeals' => 0,
                'log_cleanup' => 0,
            ],
            'notifications' => array_slice($notifications, 0, 20),
        ];
    }
}
