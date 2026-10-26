<?php

namespace App\Services;

use App\Jobs\ProcessClickLocation;
use App\Models\Shortlink;
use App\Models\ShortlinkClick;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ShortlinkService
{
    public function detectSource(Request $request): string
    {
        $utmSource = $request->query('utm_source');
        if (is_string($utmSource) && $utmSource !== '') {
            return strtolower(trim($utmSource));
        }

        $referer = $request->header('referer');
        if (! is_string($referer) || $referer === '') {
            return 'direct';
        }

        $host = parse_url($referer, PHP_URL_HOST);
        if (! is_string($host) || $host === '') {
            return 'direct';
        }

        return strtolower(preg_replace('/^www\./', '', $host));
    }

    public function recordClick(Shortlink $shortlink, Request $request): RedirectResponse
    {
        $visitorId = $request->cookie('linkan_visitor');
        $isNewVisitor = false;

        if (! $visitorId) {
            $visitorId = (string) Str::uuid();
            $isNewVisitor = true;
        }

        $click = ShortlinkClick::create([
            'shortlink_id' => $shortlink->id,
            'user_id' => $shortlink->user_id,
            'source' => $this->detectSource($request),
            'referer' => $request->header('referer'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('user-agent'),
            'visitor_id' => $visitorId,
            'device_type' => DeviceDetector::detect((string) $request->header('user-agent')),
        ]);

        ProcessClickLocation::dispatch($click->id, $request->ip());

        $response = redirect($shortlink->destination);
        if ($isNewVisitor) {
            $response->cookie('linkan_visitor', $visitorId, 60 * 24 * 365);
        }

        return $response;
    }

    public function resolveDateRange(Request $request): array
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        try {
            $start = $startDate ? Carbon::parse($startDate)->startOfDay() : now()->subDays(6)->startOfDay();
            $end = $endDate ? Carbon::parse($endDate)->endOfDay() : now()->endOfDay();
        } catch (\Throwable $e) {
            $start = now()->subDays(6)->startOfDay();
            $end = now()->endOfDay();
        }

        if ($start->gt($end)) {
            [$start, $end] = [$end->copy()->startOfDay(), $start->copy()->endOfDay()];
        }

        if ($start->diffInDays($end) > 30) {
            $end = $start->copy()->addDays(30)->endOfDay();
        }

        return [$start, $end];
    }

    public function sourceSummary(Shortlink $shortlink): array
    {
        return $shortlink->clicks()
            ->select('source', DB::raw('count(*) as total'))
            ->groupBy('source')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row) => $row->source.' ('.$row->total.')')
            ->all();
    }

    public function ipBreakdown(Shortlink $shortlink, $startDate = null, $endDate = null, $source = null): array
    {
        $query = $shortlink->clicks()->select('ip_address', DB::raw('count(*) as total'));

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        if ($source) {
            $query->where('source', $source);
        }

        return $query->groupBy('ip_address')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row) => ['label' => $row->ip_address ?? 'Unknown', 'total' => (int) $row->total])
            ->values()
            ->all();
    }

    public function deviceBreakdown(Shortlink $shortlink, $startDate = null, $endDate = null, $source = null): array
    {
        $query = $shortlink->clicks()->select('user_agent');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        if ($source) {
            $query->where('source', $source);
        }

        $clicks = $query->get();
        $breakdown = ['Mobile' => 0, 'Tablet' => 0, 'Desktop' => 0];

        foreach ($clicks as $click) {
            $type = DeviceDetector::detect((string) $click->user_agent);
            if (isset($breakdown[$type])) {
                $breakdown[$type]++;
            }
        }

        return [
            ['label' => 'Mobile', 'total' => $breakdown['Mobile']],
            ['label' => 'Tablet', 'total' => $breakdown['Tablet']],
            ['label' => 'Desktop', 'total' => $breakdown['Desktop']],
        ];
    }

    public function buildAnalyticsPayload(Shortlink $shortlink, Carbon $startDate, Carbon $endDate, ?string $sourceFilter = null): array
    {
        $clicksQuery = $shortlink->clicks()
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($sourceFilter) {
            $clicksQuery->where('source', $sourceFilter);
        }

        $clicksByDate = (clone $clicksQuery)
            ->selectRaw('DATE(created_at) as click_date, count(*) as total, count(distinct visitor_id) as unique_clicks')
            ->groupBy('click_date')
            ->get()
            ->keyBy('click_date');

        $labels = [];
        $totalClicksDaily = [];
        $uniqueClicksDaily = [];

        $cursor = $startDate->copy();
        while ($cursor->lte($endDate)) {
            $key = $cursor->toDateString();
            $labels[] = $cursor->format('d M');
            $stat = $clicksByDate->get($key);
            $totalClicksDaily[] = (int) ($stat->total ?? 0);
            $uniqueClicksDaily[] = (int) ($stat->unique_clicks ?? 0);
            $cursor->addDay();
        }

        $dayNameExpr = DB::getDriverName() === 'sqlite'
            ? "case strftime('%w', created_at) when '0' then 'Sunday' when '1' then 'Monday' when '2' then 'Tuesday' when '3' then 'Wednesday' when '4' then 'Thursday' when '5' then 'Friday' when '6' then 'Saturday' end"
            : 'DAYNAME(created_at)';

        $timeBehavior = (clone $clicksQuery)
            ->selectRaw("{$dayNameExpr} as day_name, count(*) as total")
            ->groupBy('day_name')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row) => ['label' => $row->day_name, 'total' => (int) $row->total])
            ->values()
            ->all();

        $geoBreakdown = (clone $clicksQuery)
            ->select('country', DB::raw('count(*) as total'))
            ->whereNotNull('country')
            ->groupBy('country')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row) => ['label' => $row->country, 'total' => (int) $row->total])
            ->values()
            ->all();

        $deviceBreakdown = (clone $clicksQuery)
            ->select('device_type', DB::raw('count(*) as total'))
            ->whereNotNull('device_type')
            ->groupBy('device_type')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row) => ['label' => $row->device_type, 'total' => (int) $row->total])
            ->values()
            ->all();

        if (empty($deviceBreakdown)) {
            $deviceBreakdown = $this->deviceBreakdown($shortlink, $startDate, $endDate, $sourceFilter);
        }

        $sourcesQuery = $shortlink->clicks()
            ->whereBetween('created_at', [$startDate, $endDate]);

        return [
            'labels' => $labels,
            'clicks' => $totalClicksDaily,
            'unique_clicks' => $uniqueClicksDaily,
            'time_behavior' => $timeBehavior,
            'geo_breakdown' => $geoBreakdown,
            'device_breakdown' => $deviceBreakdown,
            'ip_breakdown' => $this->ipBreakdown($shortlink, $startDate, $endDate, $sourceFilter),
            'sources' => $sourcesQuery
                ->select('source', DB::raw('count(*) as total'))
                ->groupBy('source')
                ->orderByDesc('total')
                ->get()
                ->map(fn ($row) => ['label' => $row->source, 'total' => (int) $row->total])
                ->values()
                ->all(),
            'total_clicks' => (clone $clicksQuery)->count(),
            'unique_total' => (clone $clicksQuery)->distinct('visitor_id')->count('visitor_id'),
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
        ];
    }

    public function getSellerShortlinksData(User $user, Request $request): array
    {
        $query = Shortlink::where('user_id', $user->getKey())
            ->withCount('clicks')
            ->with(['clicks' => function ($q) {
                $q->orderBy('created_at', 'desc')->limit(50);
            }]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('destination', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $sort = $request->input('sort', 'newest');
        if ($sort === 'popular') {
            $query->orderBy('clicks_count', 'desc');
        } elseif ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $shortlinks = $query->paginate(6);

        $monthExpr = DB::getDriverName() === 'sqlite'
            ? "cast(strftime('%m', created_at) as integer)"
            : 'MONTH(created_at)';

        $clicksPerMonth = ShortlinkClick::where('user_id', $user->getKey())
            ->whereYear('created_at', now()->year)
            ->selectRaw("{$monthExpr} as month, COUNT(*) as total")
            ->groupBy('month')
            ->pluck('total', 'month');

        $chartLabels = [];
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartLabels[] = now()->month($i)->shortMonthName;
            $chartData[] = (int) $clicksPerMonth->get($i, 0);
        }

        $totalClicksAllTime = ShortlinkClick::where('user_id', $user->getKey())->count();

        return compact('shortlinks', 'chartLabels', 'chartData', 'totalClicksAllTime');
    }

    public function storeShortlink(User $user, array $data): Shortlink
    {
        return DB::transaction(function () use ($user, $data) {
            $shortlink = Shortlink::create([
                'user_id' => $user->getKey(),
                'title' => $data['title'] ?? null,
                'description' => $data['description'] ?? null,
                'slug' => $data['slug'],
                'destination' => $data['destination'],
            ]);

            ActivityLogger::log(
                'create_shortlink',
                "Seller {$user->name} membuat shortlink baru: '{$shortlink->slug}' diarahkan ke {$shortlink->destination}",
                ['slug' => $shortlink->slug, 'destination' => $shortlink->destination],
                $user->id
            );

            return $shortlink;
        });
    }

    public function updateShortlink(User $user, Shortlink $shortlink, array $data): Shortlink
    {
        return DB::transaction(function () use ($shortlink, $user, $data) {
            $shortlink->update([
                'title' => $data['title'] ?? null,
                'slug' => $data['slug'],
                'password' => $data['password'] ?? null,
                'expires_at' => $data['expires_at'] ?? null,
            ]);

            ActivityLogger::log(
                'update_shortlink',
                'Seller '.($user->name ?? 'Seller')." memperbarui shortlink: '{$shortlink->slug}'",
                ['slug' => $shortlink->slug, 'shortlink_id' => $shortlink->id],
                $user->id ?? null
            );

            return $shortlink;
        });
    }
}
