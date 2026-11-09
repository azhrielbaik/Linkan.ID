<?php

namespace App\Services\AdminSeller\Statistics;

use App\Models\DigitalProduct;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ScorecardMetricService
{
    /**
     * Compute 8 Hero Scorecards with percentage change vs previous period.
     */
    public function getScorecards(User $user, Carbon $start, Carbon $end, Carbon $prevStart, Carbon $prevEnd): array
    {
        // 1. Total Views
        $currViews = DB::table('link_views')
            ->where('user_id', $user->id)
            ->whereBetween('created_at', [$start, $end])
            ->count();
        $prevViews = DB::table('link_views')
            ->where('user_id', $user->id)
            ->whereBetween('created_at', [$prevStart, $prevEnd])
            ->count();

        // 2. Total Clicks (Combined shortlink clicks)
        $currClicks = DB::table('shortlink_clicks')
            ->where('user_id', $user->id)
            ->whereBetween('created_at', [$start, $end])
            ->count();
        $prevClicks = DB::table('shortlink_clicks')
            ->where('user_id', $user->id)
            ->whereBetween('created_at', [$prevStart, $prevEnd])
            ->count();

        // 3. Total Pendapatan / Sales (Success transactions)
        $currSales = (float) DB::table('transactions')
            ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
            ->where('digital_products.user_id', $user->id)
            ->where('transactions.status', 'success')
            ->whereBetween('transactions.created_at', [$start, $end])
            ->sum('transactions.total_price');
        $prevSales = (float) DB::table('transactions')
            ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
            ->where('digital_products.user_id', $user->id)
            ->where('transactions.status', 'success')
            ->whereBetween('transactions.created_at', [$prevStart, $prevEnd])
            ->sum('transactions.total_price');

        // 4. Total Transaksi (Success orders count)
        $currTrans = DB::table('transactions')
            ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
            ->where('digital_products.user_id', $user->id)
            ->where('transactions.status', 'success')
            ->whereBetween('transactions.created_at', [$start, $end])
            ->count();
        $prevTrans = DB::table('transactions')
            ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
            ->where('digital_products.user_id', $user->id)
            ->where('transactions.status', 'success')
            ->whereBetween('transactions.created_at', [$prevStart, $prevEnd])
            ->count();

        // 5. Balance Saat Ini (Ready to withdraw)
        $currentBalance = (float) ($user->balance ?? 0.0);

        // 6. Average Order Value (AOV)
        $currAov = $currTrans > 0 ? ($currSales / $currTrans) : 0.0;
        $prevAov = $prevTrans > 0 ? ($prevSales / $prevTrans) : 0.0;

        // 7. Active Digital Products
        $activeProducts = DigitalProduct::where('user_id', $user->id)
            ->where('is_active', 1)
            ->count();
        $totalProducts = DigitalProduct::where('user_id', $user->id)->count();

        // 8. Conversion Rate (% of views resulting in transactions)
        $currConvRate = $currViews > 0 ? round(($currTrans / $currViews) * 100, 2) : 0.0;
        $prevConvRate = $prevViews > 0 ? round(($prevTrans / $prevViews) * 100, 2) : 0.0;

        return [
            'views' => $this->formatMetric($currViews, $prevViews),
            'clicks' => $this->formatMetric($currClicks, $prevClicks),
            'sales' => $this->formatMetric($currSales, $prevSales, true),
            'transactions' => $this->formatMetric($currTrans, $prevTrans),
            'balance' => [
                'value' => $currentBalance,
                'formatted' => 'Rp '.number_format($currentBalance, 0, ',', '.'),
            ],
            'aov' => $this->formatMetric($currAov, $prevAov, true),
            'products' => [
                'active' => $activeProducts,
                'total' => $totalProducts,
                'formatted' => "{$activeProducts} / {$totalProducts}",
            ],
            'conversion_rate' => $this->formatMetric($currConvRate, $prevConvRate, false, '%'),
        ];
    }

    /**
     * Helper to format score metrics with comparison delta and trend indicators.
     */
    public function formatMetric(float|int $current, float|int $previous, bool $isCurrency = false, string $suffix = ''): array
    {
        if ($previous == 0) {
            $changePct = $current > 0 ? 100.0 : 0.0;
        } else {
            $changePct = round((($current - $previous) / $previous) * 100, 1);
        }

        $trend = $changePct > 0 ? 'up' : ($changePct < 0 ? 'down' : 'neutral');
        $trendClass = $trend === 'up' ? 'delta-up' : ($trend === 'down' ? 'delta-down' : 'delta-neutral');
        $signedChange = ($changePct > 0 ? '+' : '').$changePct.'%';

        if ($isCurrency) {
            $formatted = 'Rp '.number_format($current, 0, ',', '.');
        } elseif ($suffix === '%') {
            $formatted = number_format($current, 1, ',', '.').'%';
        } else {
            $formatted = number_format($current, 0, ',', '.');
        }

        return [
            'value' => $current,
            'previous' => $previous,
            'formatted' => $formatted,
            'change_pct' => abs($changePct),
            'signed_change' => $signedChange,
            'trend' => $trend,
            'trend_class' => $trendClass,
        ];
    }
}
