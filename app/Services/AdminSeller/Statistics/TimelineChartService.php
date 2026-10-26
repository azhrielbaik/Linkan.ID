<?php

namespace App\Services\AdminSeller\Statistics;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TimelineChartService
{
    /**
     * Daily timeline series for traffic & revenue.
     */
    public function getTimelineChartData(User $user, Carbon $start, Carbon $end): array
    {
        $cursor = $start->copy();
        $labels = [];
        $views = [];
        $clicks = [];
        $sales = [];

        // Pre-query daily counts for optimal performance
        $viewsMap = DB::table('link_views')
            ->where('user_id', $user->id)
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as date, count(*) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $clicksMap = DB::table('shortlink_clicks')
            ->where('user_id', $user->id)
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as date, count(*) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $salesMap = DB::table('transactions')
            ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
            ->where('digital_products.user_id', $user->id)
            ->where('transactions.status', 'success')
            ->whereBetween('transactions.created_at', [$start, $end])
            ->selectRaw('DATE(transactions.created_at) as date, sum(transactions.total_price) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        while ($cursor->lte($end)) {
            $key = $cursor->toDateString();
            $labels[] = $cursor->format('d M');
            $views[] = (int) ($viewsMap->get($key, 0));
            $clicks[] = (int) ($clicksMap->get($key, 0));
            $sales[] = (float) ($salesMap->get($key, 0));

            $cursor->addDay();
        }

        return [
            'labels' => $labels,
            'views' => $views,
            'clicks' => $clicks,
            'sales' => $sales,
        ];
    }

    /**
     * Transaction status breakdown and timeline health.
     */
    public function getTransactionStatusData(User $user, Carbon $start, Carbon $end): array
    {
        $query = DB::table('transactions')
            ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
            ->where('digital_products.user_id', $user->id)
            ->whereBetween('transactions.created_at', [$start, $end]);

        $statusCounts = (clone $query)
            ->select('transactions.status', DB::raw('count(*) as count'))
            ->groupBy('transactions.status')
            ->pluck('count', 'status');

        $successCount = (int) ($statusCounts['success'] ?? 0);
        $pendingCount = (int) ($statusCounts['pending'] ?? 0);
        $failedCount = (int) (($statusCounts['failed'] ?? 0) + ($statusCounts['cancelled'] ?? 0) + ($statusCounts['cancel'] ?? 0));
        $totalOrders = $successCount + $pendingCount + $failedCount;

        // Daily status breakdown
        $daily = (clone $query)
            ->selectRaw('DATE(transactions.created_at) as date, transactions.status, count(*) as count')
            ->groupBy('date', 'transactions.status')
            ->get();

        $cursor = $start->copy();
        $labels = [];
        $dailySuccess = [];
        $dailyPending = [];
        $dailyFailed = [];

        $dailyGrouped = [];
        foreach ($daily as $item) {
            $dailyGrouped[$item->date][$item->status] = (int) $item->count;
        }

        while ($cursor->lte($end)) {
            $key = $cursor->toDateString();
            $labels[] = $cursor->format('d M');
            $rec = $dailyGrouped[$key] ?? [];

            $dailySuccess[] = (int) ($rec['success'] ?? 0);
            $dailyPending[] = (int) ($rec['pending'] ?? 0);
            $dailyFailed[] = (int) (($rec['failed'] ?? 0) + ($rec['cancelled'] ?? 0) + ($rec['cancel'] ?? 0));

            $cursor->addDay();
        }

        return [
            'total' => $totalOrders,
            'success' => $successCount,
            'pending' => $pendingCount,
            'failed' => $failedCount,
            'labels' => $labels,
            'daily_success' => $dailySuccess,
            'daily_pending' => $dailyPending,
            'daily_failed' => $dailyFailed,
        ];
    }

    /**
     * Get recent transactions overview for quick preview.
     */
    public function getRecentTransactions(User $user, int $limit = 10): array
    {
        $transactions = DB::table('transactions')
            ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
            ->where('digital_products.user_id', $user->id)
            ->select(
                'transactions.id',
                'transactions.order_id',
                'transactions.buyer_name',
                'transactions.total_price',
                'transactions.status',
                'transactions.payment_method',
                'transactions.created_at',
                'digital_products.title as product_title'
            )
            ->orderByDesc('transactions.created_at')
            ->limit($limit)
            ->get();

        return $transactions->map(function ($tx) {
            $status = strtolower($tx->status);
            $statusLabel = match ($status) {
                'success', 'completed' => 'Completed',
                'failed', 'cancelled', 'cancel' => 'Cancelled',
                default => 'Pending',
            };

            $statusClass = match ($status) {
                'success', 'completed' => 'status-success',
                'failed', 'cancelled', 'cancel' => 'status-failed',
                default => 'status-pending',
            };

            return [
                'id' => $tx->id,
                'order_id' => $tx->order_id ?: ('#'.$tx->id),
                'product_title' => $tx->product_title,
                'buyer_name' => $tx->buyer_name ?: 'Anonymous',
                'price_formatted' => 'Rp '.number_format($tx->total_price, 0, ',', '.'),
                'status_label' => $statusLabel,
                'status_class' => $statusClass,
                'payment_method' => strtoupper(str_replace('_', ' ', $tx->payment_method ?: '-')),
                'time_formatted' => Carbon::parse($tx->created_at)->format('d M Y, H:i'),
            ];
        })->all();
    }
}
