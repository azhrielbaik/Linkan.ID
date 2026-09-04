<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SuspensionAppeal;
use App\Services\PlatformAdminService;
use App\Http\Resources\PlatformAdmin\SellerDetailResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PlatformAdminController extends Controller
{
    protected PlatformAdminService $platformAdminService;

    public function __construct(PlatformAdminService $platformAdminService)
    {
        $this->platformAdminService = $platformAdminService;
    }

    public function beranda()
    {
        $stats = $this->platformAdminService->getDashboardStats();
        return view('platformadmin.berandaplatform', $stats);
    }

    public function exportExcel(Request $request)
    {
        $fileName = 'laporan_komisi_platform_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename={$fileName}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
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
                'total_earnings' => 'Rp ' . number_format($totalEarnings, 0, ',', '.'),
                'total_records' => $commissions->count(),
                'commission_details' => $commissions->map(function ($c) {
                    return [
                        'name'   => $c->seller_name,
                        'email'  => $c->seller_email,
                        'date'   => \Carbon\Carbon::parse($c->created_at)->translatedFormat('d M Y, H:i'),
                        'turnover' => 'Rp ' . number_format($c->amount, 0, ',', '.'),
                        'amount' => 'Rp ' . number_format($c->commission, 0, ',', '.')
                    ];
                })->toArray()
            ];
        }

        return view('platformadmin.print', compact('data'));
    }

    public function getCommissions(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'total_earnings' => $this->platformAdminService->getTotalEarnings(),
            'commissions' => $this->platformAdminService->getCommissionsData()
        ]);
    }



    public function users(Request $request)
    {
        $search = $request->input('search');
        $filter = $request->input('filter', 'all');
        $startDate = $request->input('start_date') ?: $request->input('date', '');
        $endDate = $request->input('end_date', '');
        $appealStatus = $request->input('appeal_status');
        $viewType = $request->input('view', 'users');

        $data = $this->platformAdminService->getUsersList($search, $filter, $startDate, $endDate, $appealStatus);
        
        return view('platformadmin.users', array_merge($data, [
            'filter' => $filter,
            'search' => $search,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]));
    }

    public function userSuggest(Request $request)
    {
        $q = trim($request->query('q', ''));
        if (empty($q)) {
            return response()->json([]);
        }

        return response()->json($this->platformAdminService->getSuggestions($q));
    }

    public function suspend(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        if ($user->role === PlatformAdminService::ROLE_PLATFORM_ADMIN) {
            return back()->with('error', 'Tidak dapat men-suspend akun Platform Admin.');
        }

        $request->validate([
            'duration'       => ['required', 'string', 'in:1_day,3_days,7_days,30_days,permanent'],
            'suspend_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $reason = $request->suspend_reason ? strip_tags($request->suspend_reason) : 'Pelanggaran ketentuan penggunaan platform';
        
        $durationLabel = $this->platformAdminService->suspendUser($user, $request->duration, $reason);

        return back()->with('success', "Akun {$user->name} berhasil di-suspend dengan durasi: {$durationLabel}.");
    }

    public function activate(int $id)
    {
        $user = User::findOrFail($id);
        
        $this->platformAdminService->activateUser($user);

        return back()->with('success', "Akun {$user->name} berhasil diaktifkan kembali.");
    }

    public function approveAppeal(int $id)
    {
        $appeal = SuspensionAppeal::with('user')->findOrFail($id);

        $appeal->update([
            'status'      => 'approved',
            'admin_notes' => 'Permohonan banding disetujui. Akun telah dipulihkan.',
            'resolved_at' => now(),
        ]);

        if ($appeal->user) {
            $this->platformAdminService->activateUser($appeal->user);
        }

        \App\Services\ActivityLogger::log(
            'approve_suspension_appeal',
            "Menyetujui permohonan banding akun: {$appeal->user->name} ({$appeal->user->email})",
            ['appeal_id' => $appeal->id, 'user_id' => $appeal->user_id]
        );

        return back()->with('success', "Permohonan banding dari {$appeal->user->name} berhasil disetujui dan akun telah dipulihkan.");
    }

    public function rejectAppeal(Request $request, int $id)
    {
        $appeal = SuspensionAppeal::with('user')->findOrFail($id);

        $request->validate([
            'admin_notes' => ['required', 'string', 'max:1000'],
        ], [
            'admin_notes.required' => 'Wajib memberikan catatan alasan penolakan banding.',
        ]);

        $adminNotes = strip_tags($request->admin_notes);

        $appeal->update([
            'status'      => 'rejected',
            'admin_notes' => $adminNotes,
            'resolved_at' => now(),
        ]);

        \App\Services\ActivityLogger::log(
            'reject_suspension_appeal',
            "Menolak permohonan banding akun: {$appeal->user->name}. Catatan: {$adminNotes}",
            ['appeal_id' => $appeal->id, 'user_id' => $appeal->user_id, 'admin_notes' => $adminNotes]
        );

        return back()->with('success', "Permohonan banding dari {$appeal->user->name} telah ditolak.");
    }

    public function sellerDetail(int $id)
    {
        try {
            $user = User::with('digitalProducts')->findOrFail($id);
            return response()->json(new SellerDetailResource($user));
        } catch (\Exception $e) {
            Log::error('Error loading seller detail: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
