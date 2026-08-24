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

    private function ipBreakdown(Shortlink $shortlink): array
    {
        return $shortlink->clicks()
            ->select('ip_address', DB::raw('count(*) as total'))
            ->groupBy('ip_address')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row) => ['label' => $row->ip_address ?? 'Unknown', 'total' => (int) $row->total])
            ->values()
            ->all();
    }

    private function deviceBreakdown(Shortlink $shortlink): array
    {
        $clicks = $shortlink->clicks()->select('user_agent')->get();
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
        return view('homeadminS.shortlink.create');
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

        $createdShortlink = Shortlink::create([
            'user_id' => $user->getKey(),
            'title' => $request->title,
            'description' => $request->description,
            'slug' => $request->slug,
            'destination' => $request->destination,
        ]);

        // Catat Log Pembuatan Shortlink
        ActivityLogger::log(
            'create_shortlink',
            "Seller {$user->name} membuat shortlink baru: '{$createdShortlink->slug}' diarahkan ke {$createdShortlink->destination}",
            ['slug' => $createdShortlink->slug, 'destination' => $createdShortlink->destination],
            $user->id
        );

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

        return back()->with('success', 'Shortlink berhasil diperbarui.');
    }

    public function redirect($slug)
    {
        $shortlink = Shortlink::where('slug', $slug)->firstOrFail();

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

        ShortlinkClick::create([
            'shortlink_id' => $shortlink->id,
            'user_id' => $shortlink->user_id,
            'source' => $this->detectSource(request()),
            'referer' => request()->header('referer'),
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('user-agent'),
        ]);

        return redirect($shortlink->destination);
    }

    public function passwordForm($slug)
    {
        $shortlink = Shortlink::where('slug', $slug)->firstOrFail();
        
        // Check Expiration again
        if ($shortlink->expires_at && now()->greaterThan($shortlink->expires_at)) {
            abort(410, 'Tautan ini telah kedaluwarsa.');
        }

        return view('shortlink.password', compact('shortlink'));
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

        return view('homeadminS.shortlink.create', compact('shortlinks'));
    }

    public function analytics(Request $request, Shortlink $shortlink)
    {
        $shortlink = $this->ownedShortlink($request, $shortlink);

        [$startDate, $endDate] = $this->resolveDateRange($request);

        $totalClicks = $shortlink->clicks()->count();
        $sources = $this->sourceSummary($shortlink);

        return view('homeadminS.shortlink.analytics', [
            'shortlink' => $shortlink,
            'totalClicks' => $totalClicks,
            'sources' => $sources,
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
        ]);
    }

    public function analyticsChart(Request $request, Shortlink $shortlink)
    {
        $shortlink = $this->ownedShortlink($request, $shortlink);

        [$startDate, $endDate] = $this->resolveDateRange($request);

        $clicksByDate = $shortlink->clicks()
            ->selectRaw('DATE(created_at) as click_date, count(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('click_date')
            ->pluck('total', 'click_date');

        $labels = [];
        $clicks = [];

        $cursor = $startDate->copy();
        while ($cursor->lte($endDate)) {
            $key = $cursor->toDateString();
            $labels[] = $cursor->format('d M');
            $clicks[] = (int) ($clicksByDate[$key] ?? 0);
            $cursor->addDay();
        }

        return response()->json([
            'labels' => $labels,
            'clicks' => $clicks,
            'sources' => $shortlink->clicks()
                ->select('source', DB::raw('count(*) as total'))
                ->groupBy('source')
                ->orderByDesc('total')
                ->get()
                ->map(fn ($row) => ['label' => $row->source, 'total' => (int) $row->total])
                ->values()
                ->all(),
            'total_clicks' => $shortlink->clicks()->count(),
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'ip_breakdown' => $this->ipBreakdown($shortlink),
            'device_breakdown' => $this->deviceBreakdown($shortlink),
        ]);
    }
}
