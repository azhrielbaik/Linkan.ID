<?php

namespace App\Services\AdminSeller\Statistics;

use App\Models\DigitalProduct;
use App\Models\Shortlink;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PerformanceRankingService
{
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
                'has_password' => ! empty($link->password),
                'status' => $status,
                'analytics_url' => route('admin.shortlinks.analytics', ['shortlink' => $link->id]),
            ];
        }

        // Sort by total clicks descending
        usort($list, fn ($a, $b) => $b['total_clicks'] <=> $a['total_clicks']);

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
            if (! empty($p->image)) {
                $imageUrl = filter_var($p->image, FILTER_VALIDATE_URL) ? $p->image : asset('storage/'.$p->image);
            }

            $list[] = [
                'id' => $p->id,
                'title' => $p->title,
                'price' => (float) $p->price,
                'price_formatted' => 'Rp '.number_format($p->price, 0, ',', '.'),
                'image_url' => $imageUrl,
                'qty_sold' => $qtySold,
                'revenue' => $revenue,
                'revenue_formatted' => 'Rp '.number_format($revenue, 0, ',', '.'),
                'aov' => $aov,
                'aov_formatted' => 'Rp '.number_format($aov, 0, ',', '.'),
                'avg_rating' => $avgRating,
                'reviews_count' => $reviewsCount,
                'is_active' => (bool) $p->is_active,
                'verification_status' => $p->verification_status ?? 'approved',
                'quantity' => $p->quantity ?? 'Unlimited',
            ];
        }

        // Sort by revenue descending
        $byRevenue = $list;
        usort($byRevenue, fn ($a, $b) => $b['revenue'] <=> $a['revenue']);

        // Sort by qty sold descending
        $byQty = $list;
        usort($byQty, fn ($a, $b) => $b['qty_sold'] <=> $a['qty_sold']);

        $topRev = array_slice($byRevenue, 0, 5);
        $topQty = array_slice($byQty, 0, 5);

        return [
            'list' => $byRevenue,
            'top_revenue_labels' => array_map(fn ($item) => Str::limit($item['title'], 20), $topRev),
            'top_revenue_series' => array_column($topRev, 'revenue'),
            'top_qty_labels' => array_map(fn ($item) => Str::limit($item['title'], 20), $topQty),
            'top_qty_series' => array_column($topQty, 'qty_sold'),
        ];
    }

    /**
     * Build combined top performers list for overview tab.
     */
    public function buildTopPerformers(array $shortlinksPerf, array $productsPerf): array
    {
        $topItems = [];
        $shortlinks = $shortlinksPerf['list'] ?? [];
        $products = $productsPerf['list'] ?? [];

        foreach (array_slice($shortlinks, 0, 3) as $sl) {
            $topItems[] = [
                'title' => '/'.$sl['slug'],
                'count' => $sl['total_clicks'].' klik',
                'type' => 'Shortlink',
                'type_color' => 'teal',
                'share' => min(100, (int) ($sl['total_clicks'] * 5)),
                'result' => $sl['unique_clicks'].' unik',
            ];
        }

        foreach (array_slice($products, 0, 2) as $pr) {
            $topItems[] = [
                'title' => $pr['title'],
                'count' => $pr['qty_sold'].' terjual',
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
     */
    public function buildRankedPages(array $productsPerf, array $shortlinksPerf): array
    {
        $rankedPages = [];
        $products = $productsPerf['list'] ?? [];
        $shortlinks = $shortlinksPerf['list'] ?? [];

        foreach ($products as $p) {
            $rankedPages[] = [
                'badge' => $p['qty_sold'].' pcs',
                'title' => $p['title'],
                'sub' => $p['price_formatted'].' · Omset: '.$p['revenue_formatted'],
                'url' => route('admin.digital-products.edit', $p['id']),
            ];
        }

        foreach (array_slice($shortlinks, 0, 4) as $s) {
            $rankedPages[] = [
                'badge' => $s['total_clicks'].' klik',
                'title' => '/'.$s['slug'].' ('.$s['title'].')',
                'sub' => 'Tujuan: '.$s['destination'],
                'url' => $s['analytics_url'],
            ];
        }

        return array_slice($rankedPages, 0, 4);
    }
}
