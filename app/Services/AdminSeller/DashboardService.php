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
        $digitalProducts = DigitalProduct::where('user_id', $user->id)->get();
        $totalProducts = $digitalProducts->count();

        $totalViews = DB::table('link_views')
            ->where('link_id', $user->username)
            ->count();

        $totalClicks = DB::table('link_clicks')
            ->where('link_id', $user->username)
            ->count();

        $lifetimeOrders = DB::table('transactions')
            ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
            ->where('digital_products.user_id', $user->id)
            ->where('transactions.status', 'success')
            ->sum('transactions.qty');

        $totalEarnings = (float)DB::table('transactions')
            ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
            ->where('digital_products.user_id', $user->id)
            ->where('transactions.status', 'success')
            ->sum('transactions.total_price');

        $totalWithdrawn = (float)DB::table('payout_transactions')
            ->where('user_id', $user->id)
            ->sum('amount');

        $totalEarnings = $totalEarnings - $totalWithdrawn;

        $currentBalance = (float)(DB::table('users')->where('id', $user->id)->value('balance') ?? 0);
        if ($currentBalance != $totalEarnings) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['balance' => $totalEarnings]);
        }

        $appearance = \App\Models\Appearance::where('user_id', $user->id)->first();
        $totalShortlinks = \App\Models\Shortlink::where('user_id', $user->id)->count();
        $activeMicrosite = ($appearance && $appearance->is_active) ? 1 : 1;

        $announcements = \App\Models\BroadcastAnnouncement::where('is_active', true)->latest()->get();

        // Appeal Data
        $appealData = $this->getAppealData($user);

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
            if ($t->status === 'success') {
                $notifications[] = [
                    'id'          => 'tx_succ_' . $t->id,
                    'type'        => 'transaction',
                    'title'       => 'Pembayaran Diterima!',
                    'message'     => "Pesanan untuk produk <strong>{$t->product_title}</strong> telah berhasil dibayar (Rp " . number_format($t->total_price, 0, ',', '.') . ").",
                    'badge'       => 'Sukses',
                    'badge_class' => 'badge-tx-success',
                    'icon'        => 'fas fa-check-circle',
                    'icon_bg'     => '#dcfce7',
                    'icon_color'  => '#16a34a',
                    'url'         => route('admin.orders'),
                    'time_ago'    => $timeAgo,
                    'timestamp'   => strtotime($t->updated_at),
                ];
            } else {
                $notifications[] = [
                    'id'          => 'tx_oth_' . $t->id,
                    'type'        => 'transaction_other',
                    'title'       => 'Pembaruan Pesanan',
                    'message'     => "Pesanan produk <strong>{$t->product_title}</strong> saat ini berstatus: <em>{$t->status}</em>.",
                    'badge'       => 'Info',
                    'badge_class' => 'badge-tx-info',
                    'icon'        => 'fas fa-info-circle',
                    'icon_bg'     => '#f3f4f6',
                    'icon_color'  => '#6b7280',
                    'url'         => route('admin.orders'),
                    'time_ago'    => $timeAgo,
                    'timestamp'   => strtotime($t->updated_at),
                ];
            }
        }

        // 2. Withdrawal / Payout Updates
        $payouts = DB::table('payout_transactions')
            ->where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($payouts as $p) {
            $timeAgo = \Carbon\Carbon::parse($p->updated_at)->diffForHumans();
            if ($p->status === 'completed' || $p->status === 'success') {
                $notifications[] = [
                    'id'          => 'pay_succ_' . $p->id,
                    'type'        => 'payout',
                    'title'       => 'Penarikan Dana Berhasil',
                    'message'     => "Penarikan dana Anda sebesar Rp " . number_format($p->amount, 0, ',', '.') . " ke {$p->method} telah selesai diproses.",
                    'badge'       => 'Selesai',
                    'badge_class' => 'badge-pay-success',
                    'icon'        => 'fas fa-money-bill-wave',
                    'icon_bg'     => '#dcfce7',
                    'icon_color'  => '#16a34a',
                    'url'         => route('admin.payout.history'),
                    'time_ago'    => $timeAgo,
                    'timestamp'   => strtotime($p->updated_at),
                ];
            } elseif ($p->status === 'rejected') {
                $notifications[] = [
                    'id'          => 'pay_rej_' . $p->id,
                    'type'        => 'payout',
                    'title'       => 'Penarikan Dana Gagal',
                    'message'     => "Penarikan dana Anda (Rp " . number_format($p->amount, 0, ',', '.') . ") ditolak. Silakan hubungi admin.",
                    'badge'       => 'Ditolak',
                    'badge_class' => 'badge-pay-rejected',
                    'icon'        => 'fas fa-times-circle',
                    'icon_bg'     => '#fee2e2',
                    'icon_color'  => '#dc2626',
                    'url'         => route('admin.payout.history'),
                    'time_ago'    => $timeAgo,
                    'timestamp'   => strtotime($p->updated_at),
                ];
            }
        }

        // 3. User Suspension Alert (Current Status)
        if ($user->isSuspended()) {
            $notifications[] = [
                'id'          => 'sys_suspension',
                'type'        => 'system_alert',
                'title'       => 'AKUN DITANGGUHKAN',
                'message'     => 'Akun Anda saat ini sedang ditangguhkan. Fitur utama (termasuk penjualan) dinonaktifkan. Segera ajukan banding.',
                'badge'       => 'Penting',
                'badge_class' => 'badge-sys-alert',
                'icon'        => 'fas fa-exclamation-triangle',
                'icon_bg'     => '#fef2f2',
                'icon_color'  => '#ef4444',
                'url'         => route('admin.dashboard'),
                'time_ago'    => 'Saat Ini',
                'timestamp'   => time(), // Selalu di atas
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
            if ($appeal->status === 'approved') {
                $notifications[] = [
                    'id'          => 'appeal_app_' . $appeal->id,
                    'type'        => 'appeal',
                    'title'       => 'Banding Akun Disetujui!',
                    'message'     => 'Permohonan banding Anda telah diterima dan akun Anda telah dipulihkan kembali.',
                    'badge'       => 'Dipulihkan',
                    'badge_class' => 'badge-appeal-approved',
                    'icon'        => 'fas fa-shield-alt',
                    'icon_bg'     => '#dcfce7',
                    'icon_color'  => '#16a34a',
                    'url'         => route('admin.dashboard'),
                    'time_ago'    => $timeAgo,
                    'timestamp'   => strtotime($appeal->resolved_at ?? $appeal->updated_at),
                ];
            } else {
                $notifications[] = [
                    'id'          => 'appeal_rej_' . $appeal->id,
                    'type'        => 'appeal',
                    'title'       => 'Banding Akun Ditolak',
                    'message'     => 'Permohonan banding akun Anda ditolak oleh Admin Platform. Catatan: <em>' . e($appeal->admin_notes ?? '-') . '</em>',
                    'badge'       => 'Ditolak',
                    'badge_class' => 'badge-appeal-rejected',
                    'icon'        => 'fas fa-shield-alt',
                    'icon_bg'     => '#fee2e2',
                    'icon_color'  => '#dc2626',
                    'url'         => route('admin.dashboard'),
                    'time_ago'    => $timeAgo,
                    'timestamp'   => strtotime($appeal->resolved_at ?? $appeal->updated_at),
                ];
            }
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
                'title'       => $b->title,
                'message'     => e($b->message),
                'badge'       => 'Pengumuman',
                'badge_class' => 'badge-broadcast',
                'icon'        => 'fas fa-bullhorn',
                'icon_bg'     => '#EEF0FE',
                'icon_color'  => '#5A5BF1',
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
            'notifications' => array_slice($notifications, 0, 20)
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
        foreach ($data['notifications'] as $notification) {
            $this->markNotificationRead($user, $notification['id']);
        }
    }
}
