<?php

namespace App\Http\Controllers\AdminSeller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AdminSeller\StatisticService;

class StatisticController extends Controller
{
    protected $statisticService;

    public function __construct(StatisticService $statisticService)
    {
        $this->statisticService = $statisticService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $preset = $request->input('preset', '30days');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $analytics = $this->statisticService->getAnalyticsData($user, $preset, $startDate, $endDate);

        return view('admin_seller.features.statistics.index', compact('analytics'));
    }

    public function getChartData(Request $request)
    {
        $user = Auth::user();
        
        $preset = $request->input('preset', '30days');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        $data = $this->statisticService->getAnalyticsData($user, $preset, $startDate, $endDate);

        return response()->json($data);
    }
}
