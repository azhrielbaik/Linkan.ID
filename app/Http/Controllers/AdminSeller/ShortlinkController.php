<?php

namespace App\Http\Controllers\AdminSeller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shortlink\StoreShortlinkRequest;
use App\Http\Requests\Shortlink\UpdateShortlinkRequest;
use App\Models\Shortlink;
use App\Services\ShortlinkService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShortlinkController extends Controller
{
    protected ShortlinkService $shortlinkService;

    public function __construct(ShortlinkService $shortlinkService)
    {
        $this->shortlinkService = $shortlinkService;
    }

    private function ownedShortlink(Request $request, Shortlink $shortlink): Shortlink
    {
        $user = $request->user();

        abort_unless($user && (int) $user->getKey() === (int) $shortlink->user_id, 404);

        return $shortlink;
    }

    public function create()
    {
        return view('admin_seller.features.shortlinks.create');
    }

    public function index(Request $request)
    {
        $user = $request->user();
        abort_unless($user, 403);

        $data = $this->shortlinkService->getSellerShortlinksData($user, $request);

        return view('admin_seller.features.shortlinks.index', $data);
    }

    public function store(StoreShortlinkRequest $request)
    {
        $user = $request->user();
        abort_unless($user, 403);

        $shortlink = $this->shortlinkService->storeShortlink($user, $request->validated());

        return back()
            ->with('success', 'Shortlink berhasil dibuat: '.url('/'.$shortlink->slug))
            ->withInput();
    }

    public function update(UpdateShortlinkRequest $request, Shortlink $shortlink)
    {
        $shortlink = $this->ownedShortlink($request, $shortlink);
        $user = $request->user();

        $this->shortlinkService->updateShortlink($user, $shortlink, $request->validated());

        return back()->with('success', 'Shortlink berhasil diperbarui.');
    }

    public function analytics(Request $request, Shortlink $shortlink)
    {
        $shortlink = $this->ownedShortlink($request, $shortlink);

        [$startDate, $endDate] = $this->shortlinkService->resolveDateRange($request);

        $initialChartData = $this->shortlinkService->buildAnalyticsPayload($shortlink, $startDate, $endDate);
        $sources = $this->shortlinkService->sourceSummary($shortlink);

        return view('admin_seller.features.shortlinks.analytics', [
            'shortlink' => $shortlink,
            'totalClicks' => $initialChartData['total_clicks'],
            'uniqueClicks' => $initialChartData['unique_total'],
            'sources' => $sources,
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
            'initialChartData' => $initialChartData,
        ]);
    }

    public function analyticsChart(Request $request, Shortlink $shortlink): JsonResponse
    {
        $shortlink = $this->ownedShortlink($request, $shortlink);

        [$startDate, $endDate] = $this->shortlinkService->resolveDateRange($request);
        $sourceFilter = $request->query('source');

        $payload = $this->shortlinkService->buildAnalyticsPayload($shortlink, $startDate, $endDate, $sourceFilter);

        return response()->json($payload);
    }
}
