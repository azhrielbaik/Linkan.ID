<?php

namespace App\Http\Controllers;

use App\Models\Shortlink;
use App\Models\ShortlinkClick;
use App\Services\ActivityLogger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShortlinkController extends Controller
{
    private function ownedShortlink(Request $request, Shortlink $shortlink): Shortlink
    {
        $user = $request->user();

        abort_unless($user && (int) $user->getKey() === (int) $shortlink->user_id, 404);

        return $shortlink;
    }

    private function resolveDateRange(Request $request): array
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

    private function sourceSummary(Shortlink $shortlink): array
    {
        return $shortlink->clicks()
            ->select('source', DB::raw('count(*) as total'))
            ->groupBy('source')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row) => $row->source.' ('.$row->total.')')
            ->all();
    }

    private function detectSource(Request $request): string
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

    private function ipBreakdown(Shortlink $shortlink, $startDate = null, $endDate = null, $source = null): array
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

    private function deviceBreakdown(Shortlink $shortlink, $startDate = null, $endDate = null, $source = null): array
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
            $type = \App\Services\DeviceDetector::detect((string) $click->user_agent);
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

    public function create()
    {
        return view('admin_seller.features.shortlinks.create');
    }

    public function store(Request $request)
    {
        $user = $request->user();
        abort_unless($user, 403);

        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'slug' => 'required|alpha_dash|unique:shortlinks,slug',
            'destination' => 'required|url',
        ]);

        $createdShortlink = DB::transaction(function () use ($user, $request) {
            $shortlink = Shortlink::create([
                'user_id' => $user->getKey(),
                'title' => $request->title,
                'description' => $request->description,
                'slug' => $request->slug,
                'destination' => $request->destination,
            ]);

            // Catat Log Pembuatan Shortlink
            ActivityLogger::log(
                'create_shortlink',
                "Seller {$user->name} membuat shortlink baru: '{$shortlink->slug}' diarahkan ke {$shortlink->destination}",
                ['slug' => $shortlink->slug, 'destination' => $shortlink->destination],
                $user->id
            );

            return $shortlink;
        });

        // return back()->with('success', 'Shortlink berhasil dibuat: https://Linkan.id/' . $request->slug);

        // untuk lokal host
        return back()
            ->with('success', 'Shortlink berhasil dibuat: '.url('/'.$request->slug))
            ->withInput();

    }

    public function update(Request $request, Shortlink $shortlink)
    {
        $shortlink = $this->ownedShortlink($request, $shortlink);
        $user = $request->user();

        $request->validate([
            'title' => 'nullable|string|max:255',
            'slug' => 'required|alpha_dash|unique:shortlinks,slug,' . $shortlink->id,
            'password' => 'nullable|string',
            'expires_at' => 'nullable|date',
        ]);

        DB::transaction(function () use ($shortlink, $user, $request) {
            $shortlink->update([
                'title' => $request->title,
                'slug' => $request->slug,
                'password' => $request->password,
                'expires_at' => $request->expires_at,
            ]);

            // Catat Log Update Shortlink
            ActivityLogger::log(
                'update_shortlink',
                "Seller " . ($user->name ?? 'Seller') . " memperbarui shortlink: '{$shortlink->slug}'",
                ['slug' => $shortlink->slug, 'shortlink_id' => $shortlink->id],
                $user->id ?? null
            );
        });

        return back()->with('success', 'Shortlink berhasil diperbarui.');
    }

    public function redirect($slug)
    {
        $shortlink = Shortlink::where('slug', $slug)->first();

        if (!$shortlink) {
            // Coba cek apakah slug ini adalah alias dari sebuah microsite (Appearance)
            $appearance = \App\Models\Appearance::where('alias', $slug)->first();
            if ($appearance) {
                return app(\App\Http\Controllers\PublicPageController::class)->show($slug);
            }
            abort(404, 'Tautan tidak ditemukan.');
        }

        // Check Expiration
        if ($shortlink->expires_at && now()->greaterThan($shortlink->expires_at)) {
            abort(410, 'Tautan ini telah kedaluwarsa.');
        }

        // Check Password
        if ($shortlink->password) {
            $sessionKey = 'unlocked_shortlink_' . $shortlink->id;
            if (!session()->has($sessionKey)) {
                return redirect()->route('shortlink.password.form', ['slug' => $slug]);
            }
        }

        $visitorId = request()->cookie('linkan_visitor');
        $isNewVisitor = false;
        
        if (!$visitorId) {
            $visitorId = (string) \Illuminate\Support\Str::uuid();
            $isNewVisitor = true;
        }

        $click = ShortlinkClick::create([
            'shortlink_id' => $shortlink->id,
            'user_id' => $shortlink->user_id,
            'source' => $this->detectSource(request()),
            'referer' => request()->header('referer'),
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('user-agent'),
            'visitor_id' => $visitorId,
            'device_type' => \App\Services\DeviceDetector::detect((string) request()->header('user-agent')),
        ]);

        \App\Jobs\ProcessClickLocation::dispatch($click->id, request()->ip());

        $response = redirect($shortlink->destination);
        if ($isNewVisitor) {
            $response->cookie('linkan_visitor', $visitorId, 60 * 24 * 365);
        }

        return $response;
    }

    public function passwordForm($slug)
    {
        $shortlink = Shortlink::where('slug', $slug)->firstOrFail();
        
        // Check Expiration again
        if ($shortlink->expires_at && now()->greaterThan($shortlink->expires_at)) {
            abort(410, 'Tautan ini telah kedaluwarsa.');
        }

        return view('public.shortlinks.password', compact('shortlink'));
    }

    public function verifyPassword(Request $request, $slug)
    {
        $shortlink = Shortlink::where('slug', $slug)->firstOrFail();
        
        $request->validate(['password' => 'required']);

        if ($request->password === $shortlink->password) {
            session(['unlocked_shortlink_' . $shortlink->id => true]);
            return redirect('/' . $slug);
        }

        return back()->withErrors(['password' => 'Kata sandi salah.']);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        abort_unless($user, 403);

        $query = Shortlink::where('user_id', $user->getKey())
            ->withCount('clicks')
            ->with(['clicks' => function ($query) {
                $query->orderBy('created_at', 'desc')->limit(50);
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

        $monthExpr = \Illuminate\Support\Facades\DB::getDriverName() === 'sqlite'
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

        return view('admin_seller.features.shortlinks.index', compact(
            'shortlinks',
            'chartLabels',
            'chartData',
            'totalClicksAllTime'
        ));
    }

    public function analytics(Request $request, Shortlink $shortlink)
    {
        $shortlink = $this->ownedShortlink($request, $shortlink);

        [$startDate, $endDate] = $this->resolveDateRange($request);

        $totalClicks = $shortlink->clicks()->whereBetween('created_at', [$startDate, $endDate])->count();
        $uniqueClicks = $shortlink->clicks()->whereBetween('created_at', [$startDate, $endDate])->distinct('visitor_id')->count('visitor_id');
        $sources = $this->sourceSummary($shortlink);

        return view('admin_seller.features.shortlinks.analytics', [
            'shortlink' => $shortlink,
            'totalClicks' => $totalClicks,
            'uniqueClicks' => $uniqueClicks,
            'sources' => $sources,
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
        ]);
    }

    public function analyticsChart(Request $request, Shortlink $shortlink)
    {
        $shortlink = $this->ownedShortlink($request, $shortlink);

        [$startDate, $endDate] = $this->resolveDateRange($request);
        $sourceFilter = $request->query('source');

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
            ->map(fn($row) => ['label' => $row->day_name, 'total' => (int) $row->total])
            ->values()
            ->all();

        $geoBreakdown = (clone $clicksQuery)
            ->select('country', DB::raw('count(*) as total'))
            ->whereNotNull('country')
            ->groupBy('country')
            ->orderByDesc('total')
            ->get()
            ->map(fn($row) => ['label' => $row->country, 'total' => (int) $row->total])
            ->values()
            ->all();

        $deviceBreakdown = (clone $clicksQuery)
            ->select('device_type', DB::raw('count(*) as total'))
            ->whereNotNull('device_type')
            ->groupBy('device_type')
            ->orderByDesc('total')
            ->get()
            ->map(fn($row) => ['label' => $row->device_type, 'total' => (int) $row->total])
            ->values()
            ->all();

        if (empty($deviceBreakdown)) {
            $deviceBreakdown = $this->deviceBreakdown($shortlink, $startDate, $endDate, $sourceFilter);
        }

        // Sources list shouldn't be filtered by itself to allow switching sources
        $sourcesQuery = $shortlink->clicks()
            ->whereBetween('created_at', [$startDate, $endDate]);

        return response()->json([
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
        ]);
    }
}
