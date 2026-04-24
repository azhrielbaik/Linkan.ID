<?php

namespace App\Http\Controllers;

use App\Models\Shortlink;
use App\Models\ShortlinkClick;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShortlinkController extends Controller
{
    private function ownedShortlink(Request $request, Shortlink $shortlink): Shortlink
    {
        abort_unless($request->user() && $request->user()->id === $shortlink->user_id, 404);

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

    public function create()
    {
        return view('shortlink.create');
    }

    public function store(Request $request)
    {
        $user = $request->user();
        abort_unless($user, 403);

        $request->validate([
            'slug' => 'required|alpha_dash|unique:shortlinks,slug',
            'destination' => 'required|url',
        ]);

        Shortlink::create([
            'user_id' => $user->getKey(),
            'slug' => $request->slug,
            'destination' => $request->destination,
        ]);

        // return back()->with('success', 'Shortlink berhasil dibuat: https://Linkan.id/' . $request->slug);

        // untuk lokal host
        return back()
            ->with('success', 'Shortlink berhasil dibuat: '.url('/'.$request->slug))
            ->withInput();

    }

    public function redirect($slug)
    {
        $shortlink = Shortlink::where('slug', $slug)->firstOrFail();

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

    public function index()
    {
        $user = request()->user();
        abort_unless($user, 403);

        $shortlinks = Shortlink::where('user_id', $user->getKey())
            ->withCount('clicks')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('shortlink.create', compact('shortlinks'));
    }

    public function analytics(Request $request, Shortlink $shortlink)
    {
        $shortlink = $this->ownedShortlink($request, $shortlink);

        [$startDate, $endDate] = $this->resolveDateRange($request);

        $totalClicks = $shortlink->clicks()->count();
        $sources = $this->sourceSummary($shortlink);

        return view('shortlink.analytics', [
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
        ]);
    }
}
