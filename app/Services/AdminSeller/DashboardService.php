<?php

namespace App\Services\AdminSeller;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\DigitalProduct;
use App\Models\User;

class DashboardService
{
    /**
     * Get seller dashboard statistics.
     *
     * @param User $user
     * @return array
     */
    public function getDashboardStats(User $user): array
    {
        app(OrderService::class)->autoCancelExpiredPendingOrders($user->id, 24);

        $digitalProducts = DigitalProduct::where('user_id', $user->id)->get();
        $totalProducts = $digitalProducts->count();

        $totalViews = DB::table('link_views')
            ->where('user_id', $user->id)
            ->count();

        $totalClicks = DB::table('transactions')
            ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
            ->where('digital_products.user_id', $user->id)
            ->where('transactions.status', 'success')
            ->count();

        $lifetimeOrders = DB::table('transactions')
            ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
            ->where('digital_products.user_id', $user->id)
            ->where('transactions.status', 'success')
            ->sum('transactions.qty');

        $totalSales = (float)DB::table('transactions')
            ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
            ->where('digital_products.user_id', $user->id)
            ->where('transactions.status', 'success')
            ->sum('transactions.total_price');

        $approvedGross = (float)DB::table('payout_transactions')
            ->where('user_id', $user->id)
            ->whereIn('status', ['approved', 'completed'])
            ->sum(DB::raw('COALESCE(gross_amount, amount)'));

        $pendingGross = (float)DB::table('payout_transactions')
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->sum(DB::raw('COALESCE(gross_amount, amount)'));

        // Saldo yang tersedia: pendapatan kotor penjualan dikurangi payout yang approved dan pending
        // Transaksi payout yang berstatus 'rejected' tidak dihitung sebagai pengurang sehingga otomatis kembali ke user
        $availableBalance = max(0, $totalSales - $approvedGross - $pendingGross);

        // Self-healing: sinkronkan nilai users.balance jika terjadi inkonsistensi
        $currentBalance = (float)(DB::table('users')->where('id', $user->id)->value('balance') ?? 0);
        if ($currentBalance != $availableBalance) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['balance' => $availableBalance]);
            $currentBalance = $availableBalance;
        }

        $totalEarnings = $availableBalance;

        $appearance = \App\Models\Appearance::where('user_id', $user->id)->first();
        $totalShortlinks = \App\Models\Shortlink::where('user_id', $user->id)->count();
        $activeMicrosite = ($appearance && $appearance->is_active) ? 1 : 1;

        $announcements = \App\Models\BroadcastAnnouncement::where('is_active', true)->latest()->get();

        // Appeal Data
        $appealData = $this->getAppealData($user);

        // Recent Products for Continue Watching equivalent
        $recentProducts = DigitalProduct::where('user_id', $user->id)
            ->latest()
            ->limit(3)
            ->get();

        // Recent Transactions for "Your Lesson" table equivalent
        $recentTransactions = DB::table('transactions')
            ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
            ->where('digital_products.user_id', $user->id)
            ->select('transactions.*', 'digital_products.title as product_title')
            ->orderBy('transactions.created_at', 'desc')
            ->limit(4)
            ->get();

        // Recent Activities for Sidebar (using existing notification method)
        $activitiesResponse = $this->fetchSellerNotificationsData($user);
        $recentActivities = collect($activitiesResponse['notifications'])->take(4);

        return array_merge([
            'totalProducts' => $totalProducts,
            'totalViews' => $totalViews,
            'totalClicks' => $totalClicks,
            'totalShortlinks' => $totalShortlinks,
            'activeMicrosite' => $activeMicrosite,
            'lifetimeOrders' => $lifetimeOrders,
            'totalEarnings' => $totalEarnings,
            'appearance' => $appearance,
            'announcements' => $announcements,
            'recentProducts' => $recentProducts,
            'recentTransactions' => $recentTransactions,
            'recentActivities' => $recentActivities,
        ], $appealData);
    }

    /**
     * Calculate and return appeal data for the user.
     *
     * @param User $user
     * @return array
     */
    private function getAppealData(User $user): array
    {
        $appeals = \App\Models\SuspensionAppeal::where('user_id', $user->id)->orderBy('created_at', 'asc')->get();
        $activeAppeal = $appeals->last();
        $totalAppealsCount = $appeals->count();
        $maxAppeals = 3;
        $remainingAttempts = max(0, $maxAppeals - $totalAppealsCount);
        $canSubmitAppeal = true;
        $cooldownUntil = null;
        $remainingCooldownText = null;

        if ($totalAppealsCount >= $maxAppeals) {
            $canSubmitAppeal = false;
        } elseif ($activeAppeal && $activeAppeal->status === 'rejected') {
            $rejectedAt = \Carbon\Carbon::parse($activeAppeal->resolved_at ?? $activeAppeal->updated_at);
            $cooldownUntil = $rejectedAt->copy()->addDay();
            if (now()->lt($cooldownUntil)) {
                $canSubmitAppeal = false;
                $diff = now()->diff($cooldownUntil);
                $remainingParts = [];
                if ($diff->h > 0) $remainingParts[] = $diff->h . ' jam';
                if ($diff->i > 0) $remainingParts[] = $diff->i . ' menit';
                if (empty($remainingParts)) $remainingParts[] = $diff->s . ' detik';
                $remainingCooldownText = implode(' ', $remainingParts);
            }
        }

        return [
            'activeAppeal' => $activeAppeal,
            'totalAppealsCount' => $totalAppealsCount,
            'maxAppeals' => $maxAppeals,
            'remainingAttempts' => $remainingAttempts,
            'canSubmitAppeal' => $canSubmitAppeal,
            'cooldownUntil' => $cooldownUntil,
            'remainingCooldownText' => $remainingCooldownText,
        ];
    }

    /**
     * Fetch seller notification data.
     *
     * @param User $user
     * @return array
     */
    public function fetchSellerNotificationsData(User $user): array
    {
        $notifications = [];

        // 1. Transactions (Success & Failed/Pending/Cancel)
        $transactions = DB::table('transactions')
            ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
            ->where('digital_products.user_id', $user->id)
            ->select('transactions.*', 'digital_products.title as product_title')
            ->orderBy('transactions.updated_at', 'desc')
            ->limit(10)
            ->get();

        foreach ($transactions as $t) {
            $timeAgo = \Carbon\Carbon::parse($t->updated_at)->diffForHumans();
            $buyerName = $t->buyer_name ?? 'Seseorang';
            $isSuccess = $t->status === 'success';

            $notifications[] = [
                'id'            => 'tx_' . $t->id,
                'type'          => 'transaction',
                'status'        => $t->status,
                'title'         => $isSuccess ? 'Pembayaran Diterima!' : 'Pembaruan Pesanan',
                'message'       => $isSuccess
                    ? "{$buyerName} membeli {$t->product_title}"
                    : "Pesanan {$t->product_title} dari {$buyerName} berstatus: {$t->status}",
                'buyer_name'    => $buyerName,
                'product_title' => $t->product_title,
                'amount'        => (float) ($t->total_price ?? 0),
                'avatar_url'    => 'https://ui-avatars.com/api/?name=' . urlencode($buyerName) . '&background=random',
                'url'           => route('admin.orders'),
                'time_ago'      => $timeAgo,
                'timestamp'     => strtotime($t->updated_at),
            ];
        }

        // 2. Withdrawal / Payout Updates
        $payouts = DB::table('payout_transactions')
            ->where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($payouts as $p) {
            $timeAgo = \Carbon\Carbon::parse($p->updated_at)->diffForHumans();
            $isCompleted = in_array($p->status, ['completed', 'success', 'approved']);

            $notifications[] = [
                'id'          => 'pay_' . $p->id,
                'type'        => 'payout',
                'status'      => $isCompleted ? 'success' : 'rejected',
                'title'       => $isCompleted ? 'Penarikan Dana Berhasil' : 'Penarikan Dana Gagal',
                'message'     => $isCompleted
                    ? "Penarikan Rp " . number_format($p->amount, 0, ',', '.') . " ke {$p->method} berhasil"
                    : "Penarikan Rp " . number_format($p->amount, 0, ',', '.') . " ditolak",
                'amount'      => (float) $p->amount,
                'method'      => $p->method,
                'avatar_url'  => null,
                'url'         => route('admin.payout.history'),
                'time_ago'    => $timeAgo,
                'timestamp'   => strtotime($p->updated_at),
            ];
        }

        // 3. User Suspension Alert (Current Status)
        if ($user->isSuspended()) {
            $notifications[] = [
                'id'          => 'sys_suspension',
                'type'        => 'system_alert',
                'status'      => 'suspended',
                'title'       => 'AKUN DITANGGUHKAN',
                'message'     => 'Sistem membekukan akun Anda. Segera ajukan banding.',
                'avatar_url'  => null,
                'url'         => route('admin.dashboard'),
                'time_ago'    => 'Saat Ini',
                'timestamp'   => time(),
            ];
        }

        // 4. Appeal Status Updates (Approved/Rejected)
        $appealUpdates = \App\Models\SuspensionAppeal::where('user_id', $user->id)
            ->whereIn('status', ['approved', 'rejected'])
            ->orderBy('updated_at', 'desc')
            ->limit(3)
            ->get();

        foreach ($appealUpdates as $appeal) {
            $timeAgo = \Carbon\Carbon::parse($appeal->resolved_at ?? $appeal->updated_at)->diffForHumans();
            $isApproved = $appeal->status === 'approved';

            $notifications[] = [
                'id'          => 'appeal_' . $appeal->id,
                'type'        => 'appeal',
                'status'      => $appeal->status,
                'title'       => $isApproved ? 'Banding Akun Disetujui!' : 'Banding Akun Ditolak',
                'message'     => $isApproved
                    ? 'Banding Akun disetujui, akun dipulihkan'
                    : 'Banding Akun ditolak oleh Admin Platform',
                'avatar_url'  => null,
                'url'         => route('admin.dashboard'),
                'time_ago'    => $timeAgo,
                'timestamp'   => strtotime($appeal->resolved_at ?? $appeal->updated_at),
            ];
        }

        // 5. Broadcast Announcements Aktif
        $broadcasts = DB::table('broadcast_announcements')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($broadcasts as $b) {
            $timeAgo = \Carbon\Carbon::parse($b->created_at)->diffForHumans();
            $notifications[] = [
                'id'          => 'broadcast_' . $b->id,
                'type'        => 'broadcast',
                'status'      => 'active',
                'title'       => $b->title,
                'message'     => 'Pengumuman: ' . $b->title,
                'avatar_url'  => null,
                'url'         => route('admin.dashboard'),
                'time_ago'    => $timeAgo,
                'timestamp'   => strtotime($b->created_at),
            ];
        }

        // Urutkan berdasarkan timestamp terbaru
        usort($notifications, function ($a, $b) {
            return $b['timestamp'] - $a['timestamp'];
        });

        $readKeys = DB::table('notification_reads')
            ->where('user_id', $user->id)
            ->whereIn('notification_key', array_column($notifications, 'id'))
            ->pluck('notification_key')
            ->all();
        $readKeys = array_flip($readKeys);
        foreach ($notifications as &$notification) {
            $notification['is_read'] = isset($readKeys[$notification['id']]);
        }
        unset($notification);
        $unreadCount = count(array_filter($notifications, fn ($notification) => !$notification['is_read']));

        return [
            'status'        => 'success',
            'unread_count'  => $unreadCount,
            'notifications' => array_slice($notifications, 0, 20),
            'all_keys'      => array_column($notifications, 'id')
        ];
    }

    public function markNotificationRead(User $user, string $notificationKey): void
    {
        DB::table('notification_reads')->updateOrInsert(
            ['user_id' => $user->id, 'notification_key' => $notificationKey],
            ['updated_at' => now(), 'created_at' => now()]
        );
    }

    public function markAllNotificationsRead(User $user): void
    {
        $data = $this->fetchSellerNotificationsData($user);
        $keys = $data['all_keys'] ?? array_column($data['notifications'] ?? [], 'id');
        foreach ($keys as $key) {
            $this->markNotificationRead($user, (string) $key);
        }
    }
}
