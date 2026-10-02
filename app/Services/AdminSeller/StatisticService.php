<?php

namespace App\Services\AdminSeller;

use App\Models\User;
use App\Models\Shortlink;
use App\Models\DigitalProduct;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticService
{
    /**
     * Resolve date range and previous comparison period based on preset or custom input.
     *
     * @param string|null $preset
     * @param string|null $startDate
     * @param string|null $endDate
     * @return array [Carbon $currentStart, Carbon $currentEnd, Carbon $prevStart, Carbon $prevEnd, string $preset]
     */
    public function resolveDateRange(?string $preset = '30days', ?string $startDate = null, ?string $endDate = null): array
    {
        $preset = $preset ?: '30days';

        switch ($preset) {
            case 'today':
                $currentStart = Carbon::today()->startOfDay();
                $currentEnd = Carbon::today()->endOfDay();
                $prevStart = Carbon::yesterday()->startOfDay();
                $prevEnd = Carbon::yesterday()->endOfDay();
                break;

            case 'yesterday':
                $currentStart = Carbon::yesterday()->startOfDay();
                $currentEnd = Carbon::yesterday()->endOfDay();
                $prevStart = Carbon::yesterday()->subDay()->startOfDay();
                $prevEnd = Carbon::yesterday()->subDay()->endOfDay();
                break;

            case 'this_week':
                $currentStart = Carbon::now()->startOfWeek();
                $currentEnd = Carbon::now()->endOfDay();
                $prevStart = Carbon::now()->subWeek()->startOfWeek();
                $prevEnd = Carbon::now()->subWeek()->endOfWeek();
                break;

            case 'last_week':
                $currentStart = Carbon::now()->subWeek()->startOfWeek();
                $currentEnd = Carbon::now()->subWeek()->endOfWeek();
                $prevStart = Carbon::now()->subWeeks(2)->startOfWeek();
                $prevEnd = Carbon::now()->subWeeks(2)->endOfWeek();
                break;

            case '7days':
                $currentStart = Carbon::now()->subDays(6)->startOfDay();
                $currentEnd = Carbon::now()->endOfDay();
                $prevStart = Carbon::now()->subDays(13)->startOfDay();
                $prevEnd = Carbon::now()->subDays(7)->endOfDay();
                break;

            case 'month':
                $currentStart = Carbon::now()->startOfMonth();
                $currentEnd = Carbon::now()->endOfDay();
                $prevStart = Carbon::now()->subMonth()->startOfMonth();
                $prevEnd = Carbon::now()->subMonth()->endOfMonth();
                break;

            case 'last_month':
                $currentStart = Carbon::now()->subMonth()->startOfMonth();
                $currentEnd = Carbon::now()->subMonth()->endOfMonth();
                $prevStart = Carbon::now()->subMonths(2)->startOfMonth();
                $prevEnd = Carbon::now()->subMonths(2)->endOfMonth();
                break;

            case '90days':
                $currentStart = Carbon::now()->subDays(89)->startOfDay();
                $currentEnd = Carbon::now()->endOfDay();
                $prevStart = Carbon::now()->subDays(179)->startOfDay();
                $prevEnd = Carbon::now()->subDays(90)->endOfDay();
                break;

            case 'this_year':
                $currentStart = Carbon::now()->startOfYear();
                $currentEnd = Carbon::now()->endOfDay();
                $prevStart = Carbon::now()->subYear()->startOfYear();
                $prevEnd = Carbon::now()->subYear()->endOfYear();
                break;

            case 'last_year':
                $currentStart = Carbon::now()->subYear()->startOfYear();
                $currentEnd = Carbon::now()->subYear()->endOfYear();
                $prevStart = Carbon::now()->subYears(2)->startOfYear();
                $prevEnd = Carbon::now()->subYears(2)->endOfYear();
                break;

            case 'all_time':
                $currentStart = Carbon::now()->subYears(3)->startOfDay();
                $currentEnd = Carbon::now()->endOfDay();
                $prevStart = Carbon::now()->subYears(6)->startOfDay();
                $prevEnd = Carbon::now()->subYears(3)->subSecond();
                break;

            case 'custom':
                if ($startDate && $endDate) {
                    $currentStart = Carbon::parse($startDate)->startOfDay();
                    $currentEnd = Carbon::parse($endDate)->endOfDay();
                } else {
                    $currentStart = Carbon::now()->subDays(29)->startOfDay();
                    $currentEnd = Carbon::now()->endOfDay();
                }
                $diffDays = max(1, (int) $currentStart->diffInDays($currentEnd) + 1);
                $prevStart = $currentStart->copy()->subDays($diffDays);
                $prevEnd = $currentStart->copy()->subSecond();
                break;

            case '30days':
            default:
                $preset = '30days';
                $currentStart = Carbon::now()->subDays(29)->startOfDay();
                $currentEnd = Carbon::now()->endOfDay();
                $prevStart = Carbon::now()->subDays(59)->startOfDay();
                $prevEnd = Carbon::now()->subDays(30)->endOfDay();
                break;
        }

        return [$currentStart, $currentEnd, $prevStart, $prevEnd, $preset];
    }

    /**
     * Get comprehensive analytics data for the admin seller.
     *
     * @param User $user
     * @param string|null $preset
     * @param string|null $startDate
     * @param string|null $endDate
     * @return array
     */
    public function getAnalyticsData(User $user, ?string $preset = '30days', ?string $startDate = null, ?string $endDate = null): array
    {
        [$currentStart, $currentEnd, $prevStart, $prevEnd, $resolvedPreset] = $this->resolveDateRange($preset, $startDate, $endDate);

        $scorecards = $this->getScorecards($user, $currentStart, $currentEnd, $prevStart, $prevEnd);
        $timeline = $this->getTimelineChartData($user, $currentStart, $currentEnd);
        $transactionStatuses = $this->getTransactionStatusData($user, $currentStart, $currentEnd);
        $shortlinksPerf = $this->getShortlinksPerformance($user, $currentStart, $currentEnd);
        $productsPerf = $this->getDigitalProductsPerformance($user, $currentStart, $currentEnd);
        $audience = $this->getAudienceBreakdown($user, $currentStart, $currentEnd);
        $recentTransactions = $this->getRecentTransactions($user, 10);
        $insights = $this->generateSmartInsights($user, $scorecards, $audience, $productsPerf, $shortlinksPerf);
        $topItems = $this->buildTopPerformers($shortlinksPerf, $productsPerf);
        $rankedPages = $this->buildRankedPages($productsPerf, $shortlinksPerf);

        return [
            'status' => 'success',
            'preset' => $resolvedPreset,
            'start_date' => $currentStart->toDateString(),
            'end_date' => $currentEnd->toDateString(),
            'prev_start_date' => $prevStart->toDateString(),
            'prev_end_date' => $prevEnd->toDateString(),
            'scorecards' => $scorecards,
            'timeline' => $timeline,
            'labels' => $timeline['labels'],
            'views' => $timeline['views'],
            'clicks' => $timeline['clicks'],
            'sales' => $timeline['sales'],
            'transaction_statuses' => $transactionStatuses,
            'shortlinks' => $shortlinksPerf,
            'products' => $productsPerf,
            'audience' => $audience,
            'recent_transactions' => $recentTransactions,
            'insights' => $insights,
            'top_items' => $topItems,
            'ranked_pages' => $rankedPages,
        ];
    }

    /**
     * Backward-compatibility wrapper for legacy overview.
     */
    public function getStatisticOverview(User $user): array
    {
        return $this->getAnalyticsData($user, '30days');
    }

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
                'formatted' => 'Rp ' . number_format($currentBalance, 0, ',', '.'),
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
            ->selectRaw("DATE(transactions.created_at) as date, transactions.status, count(*) as count")
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
     * Shortlink performance table and charts data.
     */
    public function getShortlinksPerformance(User $user, Carbon $start, Carbon $end): array
    {
        $shortlinks = Shortlink::where('user_id', $user->id)
            ->select('id', 'slug', 'destination', 'title', 'created_at', 'expires_at', 'password')
            ->get();

        $shortlinkIds = $shortlinks->pluck('id')->all();

        // Aggregated clicks in period
        $clicksMap = DB::table('shortlink_clicks')
            ->whereIn('shortlink_id', $shortlinkIds)
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('shortlink_id, count(*) as total_clicks, count(distinct visitor_id) as unique_clicks')
            ->groupBy('shortlink_id')
            ->get()
            ->keyBy('shortlink_id');

        // Top source per shortlink
        $sources = DB::table('shortlink_clicks')
            ->whereIn('shortlink_id', $shortlinkIds)
            ->whereBetween('created_at', [$start, $end])
            ->select('shortlink_id', 'source', DB::raw('count(*) as count'))
            ->groupBy('shortlink_id', 'source')
            ->orderByDesc('count')
            ->get()
            ->groupBy('shortlink_id');

        // Top device per shortlink
        $devices = DB::table('shortlink_clicks')
            ->whereIn('shortlink_id', $shortlinkIds)
            ->whereBetween('created_at', [$start, $end])
            ->select('shortlink_id', 'device_type', DB::raw('count(*) as count'))
            ->groupBy('shortlink_id', 'device_type')
            ->orderByDesc('count')
            ->get()
            ->groupBy('shortlink_id');

        $list = [];
        foreach ($shortlinks as $link) {
            $stats = $clicksMap->get($link->id);
            $topSrc = $sources->get($link->id)?->first()?->source ?? 'direct';
            $topDev = $devices->get($link->id)?->first()?->device_type ?? 'desktop';

            $isExpired = $link->expires_at && Carbon::parse($link->expires_at)->isPast();
            $status = $isExpired ? 'Expired' : 'Active';

            $list[] = [
                'id' => $link->id,
                'slug' => $link->slug,
                'destination' => $link->destination,
                'title' => $link->title ?: $link->slug,
                'total_clicks' => (int) ($stats->total_clicks ?? 0),
                'unique_clicks' => (int) ($stats->unique_clicks ?? 0),
                'top_source' => ucfirst($topSrc),
                'top_device' => ucfirst($topDev),
                'created_at' => $link->created_at->format('d M Y'),
                'is_expired' => $isExpired,
                'has_password' => !empty($link->password),
                'status' => $status,
                'analytics_url' => route('admin.shortlinks.analytics', ['shortlink' => $link->id]),
            ];
        }

        // Sort by total clicks descending
        usort($list, fn($a, $b) => $b['total_clicks'] <=> $a['total_clicks']);

        $top5 = array_slice($list, 0, 5);

        return [
            'list' => $list,
            'top_labels' => array_column($top5, 'slug'),
            'top_series' => array_column($top5, 'total_clicks'),
        ];
    }

    /**
     * Digital Products performance table & charts.
     */
    public function getDigitalProductsPerformance(User $user, Carbon $start, Carbon $end): array
    {
        $products = DigitalProduct::where('user_id', $user->id)->get();
        $productIds = $products->pluck('id')->all();

        // Transaction stats in period
        $stats = DB::table('transactions')
            ->whereIn('product_id', $productIds)
            ->where('status', 'success')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('product_id, count(*) as tx_count, sum(qty) as total_qty, sum(total_price) as revenue')
            ->groupBy('product_id')
            ->get()
            ->keyBy('product_id');

        // Product reviews
        $reviews = DB::table('product_reviews')
            ->whereIn('product_id', $productIds)
            ->where('is_visible', true)
            ->selectRaw('product_id, count(*) as count, avg(rating) as avg_rating')
            ->groupBy('product_id')
            ->get()
            ->keyBy('product_id');

        $list = [];
        foreach ($products as $p) {
            $st = $stats->get($p->id);
            $rev = $reviews->get($p->id);

            $qtySold = (int) ($st->total_qty ?? 0);
            $revenue = (float) ($st->revenue ?? 0.0);
            $txCount = (int) ($st->tx_count ?? 0);
            $aov = $txCount > 0 ? ($revenue / $txCount) : 0.0;
            $avgRating = $rev ? round((float) $rev->avg_rating, 1) : 0.0;
            $reviewsCount = (int) ($rev->count ?? 0);

            $imageUrl = null;
            if (!empty($p->image)) {
                $imageUrl = filter_var($p->image, FILTER_VALIDATE_URL) ? $p->image : asset('storage/' . $p->image);
            }

            $list[] = [
                'id' => $p->id,
                'title' => $p->title,
                'price' => (float) $p->price,
                'price_formatted' => 'Rp ' . number_format($p->price, 0, ',', '.'),
                'image_url' => $imageUrl,
                'qty_sold' => $qtySold,
                'revenue' => $revenue,
                'revenue_formatted' => 'Rp ' . number_format($revenue, 0, ',', '.'),
                'aov' => $aov,
                'aov_formatted' => 'Rp ' . number_format($aov, 0, ',', '.'),
                'avg_rating' => $avgRating,
                'reviews_count' => $reviewsCount,
                'is_active' => (bool) $p->is_active,
                'verification_status' => $p->verification_status ?? 'approved',
                'quantity' => $p->quantity ?? 'Unlimited',
            ];
        }

        // Sort by revenue descending
        $byRevenue = $list;
        usort($byRevenue, fn($a, $b) => $b['revenue'] <=> $a['revenue']);

        // Sort by qty sold descending
        $byQty = $list;
        usort($byQty, fn($a, $b) => $b['qty_sold'] <=> $a['qty_sold']);

        $topRev = array_slice($byRevenue, 0, 5);
        $topQty = array_slice($byQty, 0, 5);

        return [
            'list' => $byRevenue,
            'top_revenue_labels' => array_map(fn($item) => \Illuminate\Support\Str::limit($item['title'], 20), $topRev),
            'top_revenue_series' => array_column($topRev, 'revenue'),
            'top_qty_labels' => array_map(fn($item) => \Illuminate\Support\Str::limit($item['title'], 20), $topQty),
            'top_qty_series' => array_column($topQty, 'qty_sold'),
        ];
    }

    /**
     * Audience breakdown (Device, Geo Cities & Countries, Traffic Sources, Peak Hours & Days).
     */
    public function getAudienceBreakdown(User $user, Carbon $start, Carbon $end): array
    {
        $clicksQuery = DB::table('shortlink_clicks')
            ->where('user_id', $user->id)
            ->whereBetween('created_at', [$start, $end]);

        // 1. Device Type
        $devices = (clone $clicksQuery)
            ->select('device_type', DB::raw('count(*) as count'))
            ->whereNotNull('device_type')
            ->groupBy('device_type')
            ->orderByDesc('count')
            ->get();

        $deviceLabels = [];
        $deviceSeries = [];
        foreach ($devices as $d) {
            $deviceLabels[] = ucfirst($d->device_type);
            $deviceSeries[] = (int) $d->count;
        }

        // 2. Traffic Source
        $sources = (clone $clicksQuery)
            ->select('source', DB::raw('count(*) as count'))
            ->groupBy('source')
            ->orderByDesc('count')
            ->limit(8)
            ->get();

        $sourceLabels = [];
        $sourceSeries = [];
        foreach ($sources as $s) {
            $sourceLabels[] = ucfirst($s->source ?: 'Direct');
            $sourceSeries[] = (int) $s->count;
        }

        // 3. Top 10 Cities
        $cities = (clone $clicksQuery)
            ->select('city', DB::raw('count(*) as count'))
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->where('city', '!=', '-')
            ->groupBy('city')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // 4. Top 10 Countries
        $countries = (clone $clicksQuery)
            ->select('country', DB::raw('count(*) as count'))
            ->whereNotNull('country')
            ->where('country', '!=', '')
            ->where('country', '!=', '-')
            ->groupBy('country')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // 5. Peak Hours (0 - 23)
        $hourExpr = $this->getHourExpression();
        $hours = (clone $clicksQuery)
            ->selectRaw("{$hourExpr} as hour, count(*) as count")
            ->groupBy('hour')
            ->pluck('count', 'hour');

        $peakHoursLabels = [];
        $peakHoursSeries = [];
        for ($h = 0; $h < 24; $h++) {
            $peakHoursLabels[] = sprintf('%02d:00', $h);
            $peakHoursSeries[] = (int) ($hours->get($h, 0));
        }

        // 6. Peak Days (Mon - Sun)
        $dayExpr = $this->getDayOfWeekExpression();
        $days = (clone $clicksQuery)
            ->selectRaw("{$dayExpr} as day_name, count(*) as count")
            ->groupBy('day_name')
            ->pluck('count', 'day_name');

        $dayOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $dayLabelsId = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $peakDaysSeries = [];

        foreach ($dayOrder as $engDay) {
            $peakDaysSeries[] = (int) ($days->get($engDay, 0));
        }

        return [
            'device_labels' => $deviceLabels ?: ['Mobile', 'Desktop', 'Tablet'],
            'device_series' => $deviceSeries ?: [0, 0, 0],
            'source_labels' => $sourceLabels ?: ['Direct'],
            'source_series' => $sourceSeries ?: [0],
            'cities_labels' => $cities->pluck('city')->all(),
            'cities_series' => $cities->pluck('count')->map(fn($v) => (int)$v)->all(),
            'countries_labels' => $countries->pluck('country')->all(),
            'countries_series' => $countries->pluck('count')->map(fn($v) => (int)$v)->all(),
            'peak_hours_labels' => $peakHoursLabels,
            'peak_hours_series' => $peakHoursSeries,
            'peak_days_labels' => $dayLabelsId,
            'peak_days_series' => $peakDaysSeries,
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
                'order_id' => $tx->order_id ?: ('#' . $tx->id),
                'product_title' => $tx->product_title,
                'buyer_name' => $tx->buyer_name ?: 'Anonymous',
                'price_formatted' => 'Rp ' . number_format($tx->total_price, 0, ',', '.'),
                'status_label' => $statusLabel,
                'status_class' => $statusClass,
                'payment_method' => strtoupper(str_replace('_', ' ', $tx->payment_method ?: '-')),
                'time_formatted' => Carbon::parse($tx->created_at)->format('d M Y, H:i'),
            ];
        })->all();
    }

    /**
     * Generate dynamic Smart Highlights (Insight Cerdas) based on server-side rules.
     */
    public function generateSmartInsights(User $user, array $scorecards, array $audience, array $products, array $shortlinks): array
    {
        $insights = [];

        // 1. Mobile visitor dominance
        $deviceLabels = array_map('strtolower', $audience['device_labels'] ?? []);
        $deviceSeries = $audience['device_series'] ?? [];
        $totalDevClicks = array_sum($deviceSeries);
        if ($totalDevClicks > 0) {
            $mobileIndex = array_search('mobile', $deviceLabels);
            if ($mobileIndex !== false) {
                $mobilePct = round(($deviceSeries[$mobileIndex] / $totalDevClicks) * 100);
                if ($mobilePct >= 50) {
                    $insights[] = [
                        'type' => 'info',
                        'icon' => 'fa-solid fa-mobile-screen',
                        'text' => "{$mobilePct}% audiens Anda mengakses via perangkat Mobile. Pastikan tautan dan etalase digital Anda ringkas dan responsif di layar ponsel.",
                    ];
                }
            }
        }

        // 2. Top Performing Shortlink
        if (!empty($shortlinks['list'])) {
            $topLink = $shortlinks['list'][0];
            if ($topLink['total_clicks'] > 5) {
                $insights[] = [
                    'type' => 'success',
                    'icon' => 'fa-solid fa-arrow-trend-up',
                    'text' => "Shortlink /{$topLink['slug']} menjadi tautan terpopuler dengan {$topLink['total_clicks']} klik ({$topLink['unique_clicks']} unik) pada periode ini.",
                ];
            }
        }

        // 3. Top Product Revenue Contribution
        $totalSales = (float) ($scorecards['sales']['value'] ?? 0.0);
        if (!empty($products['list']) && $totalSales > 0) {
            $topProduct = $products['list'][0];
            if ($topProduct['revenue'] > 0) {
                $sharePct = round(($topProduct['revenue'] / $totalSales) * 100);
                $insights[] = [
                    'type' => 'success',
                    'icon' => 'fa-solid fa-bag-shopping',
                    'text' => "Produk '{$topProduct['title']}' menjadi produk terlaris dengan kontribusi {$sharePct}% dari total omset Anda.",
                ];
            }
        }

        // 4. Stagnant active product warning
        if (!empty($products['list'])) {
            foreach ($products['list'] as $prod) {
                if ($prod['is_active'] && $prod['qty_sold'] === 0) {
                    $insights[] = [
                        'type' => 'warning',
                        'icon' => 'fa-solid fa-lightbulb',
                        'text' => "Produk '{$prod['title']}' belum mencatatkan transaksi pada periode ini. Pertimbangkan membuat penawaran khusus atau menyematkannya di bio profil.",
                    ];
                    break;
                }
            }
        }

        // 5. Peak Audience Activity
        $peakHours = $audience['peak_hours_series'] ?? [];
        if (!empty($peakHours) && max($peakHours) > 0) {
            $maxHourIndex = array_keys($peakHours, max($peakHours))[0];
            $peakHourStr = sprintf('%02d:00', $maxHourIndex);

            $peakDays = $audience['peak_days_series'] ?? [];
            $dayLabels = $audience['peak_days_labels'] ?? [];
            $peakDayStr = 'Rabu';
            if (!empty($peakDays) && max($peakDays) > 0) {
                $maxDayIndex = array_keys($peakDays, max($peakDays))[0];
                $peakDayStr = $dayLabels[$maxDayIndex] ?? 'Rabu';
            }

            $insights[] = [
                'type' => 'timing',
                'icon' => 'fa-solid fa-clock',
                'text' => "Waktu paling ramai pengunjung tercatat pada hari {$peakDayStr} sekitar pukul {$peakHourStr} WIB. Ini waktu terbaik untuk membagikan tautan konten baru.",
            ];
        }

        // Fallback default insight if dataset is fresh
        if (empty($insights)) {
            $insights[] = [
                'type' => 'info',
                'icon' => 'fa-solid fa-chart-line',
                'text' => 'Pantau pertumbuhan konversi profil Anda secara berkala untuk mengetahui kampanye mana yang mendatangkan penjualan terbanyak.',
            ];
        }

        return $insights;
    }

    /**
     * Helper to format score metrics with comparison delta and trend indicators.
     */
    private function formatMetric(float|int $current, float|int $previous, bool $isCurrency = false, string $suffix = ''): array
    {
        if ($previous == 0) {
            $changePct = $current > 0 ? 100.0 : 0.0;
        } else {
            $changePct = round((($current - $previous) / $previous) * 100, 1);
        }

        $trend = $changePct > 0 ? 'up' : ($changePct < 0 ? 'down' : 'neutral');
        $trendClass = $trend === 'up' ? 'delta-up' : ($trend === 'down' ? 'delta-down' : 'delta-neutral');
        $signedChange = ($changePct > 0 ? '+' : '') . $changePct . '%';

        if ($isCurrency) {
            $formatted = 'Rp ' . number_format($current, 0, ',', '.');
        } elseif ($suffix === '%') {
            $formatted = number_format($current, 1, ',', '.') . '%';
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

    /**
     * Build combined top performers list for overview tab.
     *
     * @param array $shortlinksPerf
     * @param array $productsPerf
     * @return array
     */
    public function buildTopPerformers(array $shortlinksPerf, array $productsPerf): array
    {
        $topItems = [];
        $shortlinks = $shortlinksPerf['list'] ?? [];
        $products = $productsPerf['list'] ?? [];

        foreach (array_slice($shortlinks, 0, 3) as $sl) {
            $topItems[] = [
                'title' => '/' . $sl['slug'],
                'count' => $sl['total_clicks'] . ' klik',
                'type' => 'Shortlink',
                'type_color' => 'teal',
                'share' => min(100, (int) ($sl['total_clicks'] * 5)),
                'result' => $sl['unique_clicks'] . ' unik',
            ];
        }

        foreach (array_slice($products, 0, 2) as $pr) {
            $topItems[] = [
                'title' => $pr['title'],
                'count' => $pr['qty_sold'] . ' terjual',
                'type' => 'Produk',
                'type_color' => 'orange',
                'share' => min(100, (int) ($pr['qty_sold'] * 15)),
                'result' => $pr['revenue_formatted'],
            ];
        }

        return $topItems;
    }

    /**
     * Build ranked pages/links for overview tab.
     *
     * @param array $productsPerf
     * @param array $shortlinksPerf
     * @return array
     */
    public function buildRankedPages(array $productsPerf, array $shortlinksPerf): array
    {
        $rankedPages = [];
        $products = $productsPerf['list'] ?? [];
        $shortlinks = $shortlinksPerf['list'] ?? [];

        foreach ($products as $p) {
            $rankedPages[] = [
                'badge' => $p['qty_sold'] . ' pcs',
                'title' => $p['title'],
                'sub' => $p['price_formatted'] . ' · Omset: ' . $p['revenue_formatted'],
                'url' => route('admin.digital-products.edit', $p['id']),
            ];
        }

        foreach (array_slice($shortlinks, 0, 4) as $s) {
            $rankedPages[] = [
                'badge' => $s['total_clicks'] . ' klik',
                'title' => '/' . $s['slug'] . ' (' . $s['title'] . ')',
                'sub' => 'Tujuan: ' . $s['destination'],
                'url' => $s['analytics_url'],
            ];
        }

        return array_slice($rankedPages, 0, 4);
    }

    /**
     * Database driver independent hour expression.
     */
    private function getHourExpression(): string
    {
        return DB::getDriverName() === 'sqlite'
            ? "cast(strftime('%H', created_at) as integer)"
            : 'HOUR(created_at)';
    }

    /**
     * Database driver independent day of week expression.
     */
    private function getDayOfWeekExpression(): string
    {
        return DB::getDriverName() === 'sqlite'
            ? "case strftime('%w', created_at) when '0' then 'Sunday' when '1' then 'Monday' when '2' then 'Tuesday' when '3' then 'Wednesday' when '4' then 'Thursday' when '5' then 'Friday' when '6' then 'Saturday' end"
            : 'DAYNAME(created_at)';
    }
}
