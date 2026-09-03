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

    public function index()
    {
        $user = Auth::user();
        $data = $this->statisticService->getStatisticOverview($user);

        return view('admin_seller.features.statistics.index', $data);
    }

    public function getChartData(Request $request)
    {
        $user = Auth::user();
        
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        $data = $this->statisticService->getChartData($user, $startDate, $endDate);

        return response()->json($data);
    }
}
