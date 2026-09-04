<?php

namespace App\Services;

use App\Models\User;
use App\Models\SuspensionAppeal;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PlatformAdminService
{
    const ROLE_PLATFORM_ADMIN = 'admin_platform';

    /**
     * Get statistics and charts for dashboard
     */
    public function getDashboardStats(): array
    {
        $totalUsers = User::where('role', '!=', self::ROLE_PLATFORM_ADMIN)->count();
        $totalTransactions = DB::table('transactions')->where('status', 'success')->count();
        $totalCommission = DB::table('platform_commissions')->sum('commission') ?? 0;
        $totalProducts = DB::table('digital_products')->count();

        // 2. Chart Pendapatan - 12 Bulan Terakhir
        $monthlyLabels = [];
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            $monthlyLabels[] = $monthDate->translatedFormat('M Y');
            $sum = DB::table('platform_commissions')
                ->whereYear('created_at', $monthDate->year)
                ->whereMonth('created_at', $monthDate->month)
                ->sum('commission') ?? 0;
            $monthlyData[] = (float)$sum;
        }

        // Chart Pendapatan - 7 Hari Terakhir
        $weeklyLabels = [];
        $weeklyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $dayDate = now()->subDays($i);
            $weeklyLabels[] = $dayDate->translatedFormat('D, d M');
            $sum = DB::table('platform_commissions')
                ->whereDate('created_at', $dayDate->toDateString())
                ->sum('commission') ?? 0;
            $weeklyData[] = (float)$sum;
        }

        // 3. Top Seller Ranking
        $topSellers = DB::table('users')
            ->where('users.role', '!=', self::ROLE_PLATFORM_ADMIN)
            ->leftJoin('platform_commissions', 'users.id', '=', 'platform_commissions.seller_id')
            ->leftJoin('digital_products', 'users.id', '=', 'digital_products.user_id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.avatar',
                DB::raw('COUNT(DISTINCT digital_products.id) as total_products'),
                DB::raw('COUNT(DISTINCT platform_commissions.id) as total_sales_count'),
                DB::raw('COALESCE(SUM(platform_commissions.commission), 0) as total_commission_earned'),
                DB::raw('COALESCE(SUM(platform_commissions.amount), 0) as total_turnover')
            )
            ->groupBy('users.id', 'users.name', 'users.email', 'users.avatar')
            ->orderByDesc('total_commission_earned')
            ->orderByDesc('total_sales_count')
            ->limit(5)
            ->get();

        // 4. Riwayat Komisi Terkini
        $commissions = DB::table('platform_commissions')
            ->join('users as sellers', 'platform_commissions.seller_id', '=', 'sellers.id')
            ->select(
                'platform_commissions.*',
                'sellers.name as seller_name',
                'sellers.email as seller_email'
            )
            ->orderBy('platform_commissions.created_at', 'desc')
            ->limit(10)
            ->get();

        return compact(
            'totalUsers',
            'totalTransactions',
            'totalCommission',
            'totalProducts',
            'monthlyLabels',
            'monthlyData',
            'weeklyLabels',
            'weeklyData',
            'topSellers',
            'commissions'
        );
    }

    /**
     * Fetch raw notifications data for SSE
     */
    public function getNotificationsData(): array
    {
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
            $notifications[] = [
                'id'           => 'prod_' . $prod->id,
                'type'         => 'product',
                'title'        => 'Verifikasi Produk Baru',
                'seller_name'  => $prod->seller_name,
                'product_name' => $prod->product_name,
                'badge'        => 'Verifikasi',
                'badge_class'  => 'badge-product',
                'icon'         => 'fas fa-box-open',
                'icon_bg'      => '#EEF0FE',
                'icon_color'   => '#5A5BF1',
                'url'          => route('platform-admin.verifikasi'),
                'time_ago'     => Carbon::parse($prod->created_at)->diffForHumans(),
                'timestamp'    => strtotime($prod->created_at),
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
            $notifications[] = [
                'id'           => 'payout_' . $payout->id,
                'type'         => 'payout',
                'title'        => 'Permintaan Payout Baru',
                'seller_name'  => $payout->seller_name,
                'amount'       => number_format($payout->amount, 0, ',', '.'),
                'bank'         => $payout->bank_name ?? strtoupper($payout->method),
                'badge'        => 'Payout',
                'badge_class'  => 'badge-payout',
                'icon'         => 'fas fa-money-bill-wave',
                'icon_bg'      => '#fef3c7',
                'icon_color'   => '#d97706',
                'url'          => route('platform-admin.payouts.index'),
                'time_ago'     => Carbon::parse($payout->created_at)->diffForHumans(),
                'timestamp'    => strtotime($payout->created_at),
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
            $notifications[] = [
                'id'           => 'appeal_' . $appeal->id,
                'type'         => 'appeal',
                'title'        => 'Permohonan Banding Akun',
                'seller_name'  => $appeal->seller_name,
                'badge'        => 'Banding',
                'badge_class'  => 'badge-appeal',
                'icon'         => 'fas fa-shield-alt',
                'icon_bg'      => '#fee2e2',
                'icon_color'   => '#dc2626',
                'url'          => route('platform-admin.users', ['view' => 'appeals']),
                'time_ago'     => Carbon::parse($appeal->created_at)->diffForHumans(),
                'timestamp'    => strtotime($appeal->created_at),
            ];
        }

        usort($notifications, fn ($a, $b) => $b['timestamp'] - $a['timestamp']);

        return [
            'status'        => 'success',
            'unread_count'  => count($pendingProducts) + count($pendingPayouts) + count($pendingAppeals),
            'counts'        => [
                'products' => count($pendingProducts),
                'payouts'  => count($pendingPayouts),
                'appeals'  => count($pendingAppeals),
            ],
            'notifications' => array_slice($notifications, 0, 20)
        ];
    }

    /**
     * Get commissions data for export or API
     */
    public function getCommissionsData()
    {
        return DB::table('platform_commissions')
            ->join('users as sellers', 'platform_commissions.seller_id', '=', 'sellers.id')
            ->select(
                'platform_commissions.id',
                'platform_commissions.seller_id',
                'platform_commissions.amount',
                'platform_commissions.commission',
                'platform_commissions.created_at',
                'sellers.name as seller_name',
                'sellers.email as seller_email'
            )
            ->orderBy('platform_commissions.created_at', 'desc')
            ->get();
    }
    
    /**
     * Get total platform earnings
     */
    public function getTotalEarnings(): float
    {
        return (float) (DB::table('platform_commissions')->sum('commission') ?? 0);
    }

    /**
     * Generate CSV content stream for Export
     */
    public function streamCommissionsCsv(callable $callback)
    {
        $commissions = $this->getCommissionsData();
        $columns = ['ID', 'Nama Seller', 'Email Seller', 'Nominal Transaksi (IDR)', 'Komisi Platform (IDR)', 'Waktu'];

        return function () use ($callback, $commissions, $columns) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // Add BOM for UTF-8 Excel support
            fputcsv($file, $columns);

            foreach ($commissions as $row) {
                fputcsv($file, [
                    $row->id,
                    $row->seller_name,
                    $row->seller_email,
                    $row->amount,
                    $row->commission,
                    $row->created_at,
                ]);
            }
            fclose($file);
            if (is_callable($callback)) {
                $callback();
            }
        };
    }

    /**
     * Get users list with filters
     */
    public function getUsersList($search, $filter, $startDate, $endDate, $appealStatus)
    {
        $query = User::with(['suspensionAppeals' => function ($q) {
            $q->where('status', 'pending')->latest()->limit(1);
        }])->where('role', '!=', self::ROLE_PLATFORM_ADMIN);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($filter === 'active') {
            $query->where(function ($q) {
                $q->whereNull('suspended_at')
                  ->orWhere(function ($sub) {
                      $sub->whereNotNull('suspended_until')->where('suspended_until', '<=', now());
                  });
            });
        } elseif ($filter === 'suspended') {
            $query->whereNotNull('suspended_at')->where(function ($q) {
                $q->whereNull('suspended_until')
                  ->orWhere('suspended_until', '>', now());
            });
        }

        if ($startDate && $endDate) {
            $query->whereDate('created_at', '>=', $startDate)
                  ->whereDate('created_at', '<=', $endDate);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $totalUsers     = User::where('role', '!=', self::ROLE_PLATFORM_ADMIN)->count();
        $totalActive    = User::where('role', '!=', self::ROLE_PLATFORM_ADMIN)->where(function ($q) {
            $q->whereNull('suspended_at')
              ->orWhere(function ($sub) {
                  $sub->whereNotNull('suspended_until')->where('suspended_until', '<=', now());
              });
        })->count();
        $totalSuspended = User::where('role', '!=', self::ROLE_PLATFORM_ADMIN)->whereNotNull('suspended_at')->where(function ($q) {
            $q->whereNull('suspended_until')
              ->orWhere('suspended_until', '>', now());
        })->count();

        // Appeals
        $appealsQuery = SuspensionAppeal::with('user')->latest();
        if ($appealStatus) {
            $appealsQuery->where('status', $appealStatus);
        }
        $appeals = $appealsQuery->paginate(15, ['*'], 'appeals_page')->withQueryString();
        $pendingAppealsCount = SuspensionAppeal::where('status', 'pending')->count();

        return compact('users', 'totalUsers', 'totalActive', 'totalSuspended', 'appeals', 'pendingAppealsCount');
    }

    /**
     * Get suggestions for user search
     */
    public function getSuggestions(string $q): array
    {
        $users = User::where('role', '!=', self::ROLE_PLATFORM_ADMIN)
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%");
            })
            ->limit(10)
            ->get();

        $suggestions = collect();
        foreach ($users as $user) {
            if ($user->name && stripos($user->name, $q) !== false) {
                $suggestions->push(['label' => $user->name . ' (Nama)', 'value' => $user->name]);
            }
            if ($user->email && stripos($user->email, $q) !== false) {
                $suggestions->push(['label' => $user->email . ' (Email)', 'value' => $user->email]);
            }
        }

        return $suggestions->unique('value')->values()->take(5)->toArray();
    }

    /**
     * Suspend user logic
     */
    public function suspendUser(User $user, string $duration, ?string $reason): string
    {
        $suspendedUntil = null;
        $durationLabel = 'Permanen';

        switch ($duration) {
            case '1_day':
                $suspendedUntil = now()->addDay();
                $durationLabel = '1 Hari (s.d ' . $suspendedUntil->format('d M Y, H:i') . ')';
                break;
            case '3_days':
                $suspendedUntil = now()->addDays(3);
                $durationLabel = '3 Hari (s.d ' . $suspendedUntil->format('d M Y, H:i') . ')';
                break;
            case '7_days':
                $suspendedUntil = now()->addDays(7);
                $durationLabel = '7 Hari / 1 Minggu (s.d ' . $suspendedUntil->format('d M Y, H:i') . ')';
                break;
            case '30_days':
                $suspendedUntil = now()->addDays(30);
                $durationLabel = '30 Hari / 1 Bulan (s.d ' . $suspendedUntil->format('d M Y, H:i') . ')';
                break;
            case 'permanent':
            default:
                break;
        }

        $user->update([
            'suspended_at'    => now(),
            'suspended_until' => $suspendedUntil,
            'suspend_reason'  => $reason,
        ]);

        ActivityLogger::log(
            'suspend_user',
            "Men-suspend akun user: {$user->name} ({$user->email}) - Durasi: {$durationLabel}. Alasan: {$reason}",
            [
                'target_user_id'  => $user->id,
                'user_name'       => $user->name,
                'user_email'      => $user->email,
                'duration'        => $duration,
                'suspended_until' => $suspendedUntil ? $suspendedUntil->toDateTimeString() : null,
                'reason'          => $reason
            ]
        );

        return $durationLabel;
    }

    /**
     * Activate user logic
     */
    public function activateUser(User $user): void
    {
        $user->update([
            'suspended_at'    => null,
            'suspended_until' => null,
            'suspend_reason'  => null,
        ]);

        SuspensionAppeal::where('user_id', $user->id)
            ->where('status', 'pending')
            ->update([
                'status'      => 'approved',
                'admin_notes' => 'Akun dipulihkan secara manual oleh Admin Platform.',
                'resolved_at' => now(),
            ]);

        ActivityLogger::log(
            'activate_user',
            "Mengaktifkan kembali akun user: {$user->name} ({$user->email})",
            ['target_user_id' => $user->id, 'user_name' => $user->name, 'user_email' => $user->email]
        );
    }
}
