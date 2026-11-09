<?php

namespace App\Http\Controllers\PlatformAdmin;

use App\Http\Controllers\Controller;
use App\Services\PlatformAdminService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportExportController extends Controller
{
    protected PlatformAdminService $platformAdminService;

    public function __construct(PlatformAdminService $platformAdminService)
    {
        $this->platformAdminService = $platformAdminService;
    }

    public function exportExcel(Request $request)
    {
        $fileName = 'laporan_komisi_platform_'.date('Y-m-d_H-i-s').'.csv';
        $headers = [
            'Content-type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$fileName}",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream($this->platformAdminService->streamCommissionsCsv(null), 200, $headers);
    }

    public function print(Request $request)
    {
        $data = [];

        if ($request->isMethod('post') && $request->has('data')) {
            $inputData = $request->input('data');
            $data = is_string($inputData) ? json_decode($inputData, true) : $inputData;
        }

        if (empty($data) || empty($data['commission_details'])) {
            $totalEarnings = $this->platformAdminService->getTotalEarnings();
            $commissions = $this->platformAdminService->getCommissionsData();

            $data = [
                'total_earnings' => 'Rp '.number_format($totalEarnings, 0, ',', '.'),
                'total_records' => $commissions->count(),
                'commission_details' => $commissions->map(function ($c) {
                    return [
                        'name' => $c->seller_name,
                        'email' => $c->seller_email,
                        'date' => Carbon::parse($c->created_at)->translatedFormat('d M Y, H:i'),
                        'turnover' => 'Rp '.number_format($c->amount, 0, ',', '.'),
                        'amount' => 'Rp '.number_format($c->commission, 0, ',', '.'),
                    ];
                })->toArray(),
            ];
        }

        return view('platformadmin.print', compact('data'));
    }
}
