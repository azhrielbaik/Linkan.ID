<?php

namespace App\Services\PlatformAdmin;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PlatformDashboardService
{
    const ROLE_PLATFORM_ADMIN = 'admin_platform';

    /**
     * Get statistics and charts for dashboard
     */
    public function getDashboardStats(): array
    {
        return Cache::remember('platform_admin_dashboard_stats', 300, function () {
            $totalUsers = User::where('role', '!=', self::ROLE_PLATFORM_ADMIN)->count();
            $totalTransactions = DB::table('transactions')->where('status', 'success')->count();
            $totalCommission = DB::table('platform_commissions')->sum('commission') ?? 0;
            $totalProducts = DB::table('digital_products')->count();

            // Chart Pendapatan Harian - 30 Hari Terakhir (Harian)
            $start30Days = now()->subDays(29)->startOfDay();
            $daily30Sums = DB::table('platform_commissions')
                ->where('created_at', '>=', $start30Days)
                ->selectRaw('DATE(created_at) as date_val, SUM(commission) as total')
                ->groupBy('date_val')
                ->pluck('total', 'date_val');

            $daily30Labels = [];
            $daily30Data = [];
            for ($i = 29; $i >= 0; $i--) {
                $dayDate = now()->subDays($i);
                $daily30Labels[] = $dayDate->translatedFormat('d M');
                $key = $dayDate->toDateString();
                $daily30Data[] = (float) ($daily30Sums[$key] ?? 0);
            }

            // Chart Pendapatan Harian - 7 Hari Terakhir (Harian)
            $start7Days = now()->subDays(6)->startOfDay();
            $daily7Sums = DB::table('platform_commissions')
                ->where('created_at', '>=', $start7Days)
                ->selectRaw('DATE(created_at) as date_val, SUM(commission) as total')
                ->groupBy('date_val')
                ->pluck('total', 'date_val');

            $daily7Labels = [];
            $daily7Data = [];
            for ($i = 6; $i >= 0; $i--) {
                $dayDate = now()->subDays($i);
                $daily7Labels[] = $dayDate->translatedFormat('d M');
                $key = $dayDate->toDateString();
                $daily7Data[] = (float) ($daily7Sums[$key] ?? 0);
            }

            // Top Seller Ranking
            $salesBySeller = DB::table('transactions')
                ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
                ->where('transactions.status', 'success')
                ->select(
                    'digital_products.user_id as seller_id',
                    DB::raw('COUNT(transactions.id) as total_sales_count'),
                    DB::raw('SUM(transactions.total_price) as total_turnover')
                )
                ->groupBy('digital_products.user_id')
                ->orderByDesc('total_sales_count')
                ->orderByDesc('total_turnover')
                ->limit(5)
                ->get()
                ->keyBy('seller_id');

            $topSellerIds = $salesBySeller->keys()->toArray();

            // Jika seller kurang dari 5, lengkapi dari seller lain agar slot top 5 terisi
            if (count($topSellerIds) < 5) {
                $fallbackIds = DB::table('users')
                    ->where('role', '!=', self::ROLE_PLATFORM_ADMIN)
                    ->whereNotIn('id', $topSellerIds)
                    ->limit(5 - count($topSellerIds))
                    ->pluck('id')
                    ->toArray();

                $topSellerIds = array_merge($topSellerIds, $fallbackIds);
            }

            $sellersData = DB::table('users')
                ->whereIn('id', $topSellerIds)
                ->select('id', 'name', 'email', 'avatar')
                ->get()
                ->keyBy('id');

            $productCounts = DB::table('digital_products')
                ->whereIn('user_id', $topSellerIds)
                ->select('user_id', DB::raw('COUNT(id) as total'))
                ->groupBy('user_id')
                ->pluck('total', 'user_id');

            $commissionSums = DB::table('platform_commissions')
                ->whereIn('seller_id', $topSellerIds)
                ->select('seller_id', DB::raw('SUM(commission) as total_comm'))
                ->groupBy('seller_id')
                ->pluck('total_comm', 'seller_id');

            $topSellers = collect();
            foreach ($topSellerIds as $sId) {
                if (! isset($sellersData[$sId])) {
                    continue;
                }
                $seller = $sellersData[$sId];
                $sale = $salesBySeller->get($sId);

                $seller->total_products = (int) ($productCounts[$sId] ?? 0);
                $seller->total_sales_count = (int) ($sale->total_sales_count ?? 0);
                $seller->total_turnover = (float) ($sale->total_turnover ?? 0);
                $seller->total_commission_earned = (float) ($commissionSums[$sId] ?? 0);

                $topSellers->push($seller);
            }

            // Riwayat Komisi Terkini
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
                'daily7Labels',
                'daily7Data',
                'daily30Labels',
                'daily30Data',
                'topSellers',
                'commissions'
            );
        });
    }

    /**
     * Hapus cache data beranda platform.
     */
    public function clearDashboardCache(): void
    {
        Cache::forget('platform_admin_dashboard_stats');
    }
}
