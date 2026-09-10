<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SuspensionAppeal;
use App\Services\PlatformAdminService;
use App\Http\Requests\PlatformAdmin\ActivateUserRequest;
use App\Http\Requests\PlatformAdmin\ApproveAppealRequest;
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

    public function getNotifications(Request $request)
    {
        $readKeys = session('platform_notif_read_keys', []);
        return response()->json($this->platformAdminService->getNotificationsData($readKeys));
    }

    public function markNotificationRead(Request $request)
    {
        $request->validate([
            'notification_key' => 'required|string|max:100',
        ]);

        $key = $request->input('notification_key');
        $readKeys = session('platform_notif_read_keys', []);

        if (!in_array($key, $readKeys, true)) {
            $readKeys[] = $key;
            // Batasi max 200 entri agar session tidak membengkak
            if (count($readKeys) > 200) {
                $readKeys = array_slice($readKeys, -200);
            }
            session(['platform_notif_read_keys' => $readKeys]);
        }

        return response()->json(['status' => 'success', 'key' => $key]);
    }

    public function markAllNotificationsRead(Request $request)
    {
        // Ambil semua notifikasi saat ini dan tandai semuanya sebagai dibaca
        $currentData = $this->platformAdminService->getNotificationsData([]);
        $allKeys = array_map(fn ($n) => $n['id'], $currentData['notifications'] ?? []);

        $readKeys = session('platform_notif_read_keys', []);
        $merged = array_unique(array_merge($readKeys, $allKeys));

        // Batasi max 200 entri
        if (count($merged) > 200) {
            $merged = array_slice($merged, -200);
        }

        session(['platform_notif_read_keys' => $merged]);

        return response()->json(['status' => 'success', 'marked_count' => count($allKeys)]);
    }

    public function streamNotifications(Request $request)
    {
        // Ambil readKeys dari session sebelum session ditutup untuk SSE
        $readKeys = session('platform_notif_read_keys', []);

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }

        return response()->stream(function () use ($readKeys) {
            @set_time_limit(0);
            @ini_set('implicit_flush', 1);
            if (ob_get_level()) {
                @ob_end_flush();
            }
            flush();

            $maxCycles = 10;
            $lastHash = null;

            for ($i = 0; $i < $maxCycles; $i++) {
                if (connection_aborted()) {
                    break;
                }

                $data = $this->platformAdminService->getNotificationsData($readKeys);
                $currentHash = md5(json_encode($data));

                if ($lastHash !== $currentHash || $i === 0) {
                    echo "event: notifications\n";
                    echo "data: " . json_encode($data) . "\n\n";
                    $lastHash = $currentHash;
                } else {
                    echo ": ping\n\n";
                }

                if (ob_get_level()) {
                    @ob_flush();
                }
                flush();

                sleep(3);
            }
        }, 200, [
            'Content-Type'      => 'text/event-stream',
            'Cache-Control'     => 'no-cache, no-store, must-revalidate',
            'Connection'        => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    public function appeals(Request $request)
    {
        $request->merge(['view' => 'appeals']);
        return $this->users($request);
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

    public function activate(ActivateUserRequest $request, int $id)
    {
        $user = User::findOrFail($id);

        if ($user->hasRole('admin_platform')) {
            return back()->with('error', 'Tidak dapat mengubah status akun sesama Platform Admin.');
        }

        if (!$user->isSuspended()) {
            return back()->with('info', "Akun {$user->name} saat ini dalam kondisi aktif.");
        }

        $reason = $request->validated('activate_reason')
            ? strip_tags($request->validated('activate_reason'))
            : null;

        $this->platformAdminService->activateUser($user, $reason);

        return back()->with('success', "Akun {$user->name} berhasil diaktifkan kembali.");
    }

    public function approveAppeal(ApproveAppealRequest $request, int $id)
    {
        $appeal = SuspensionAppeal::with('user')->findOrFail($id);

        if ($appeal->status !== 'pending') {
            return back()->with('error', 'Permohonan banding ini telah diproses sebelumnya.');
        }

        $adminNotes = $request->validated('admin_notes')
            ? strip_tags($request->validated('admin_notes'))
            : 'Permohonan banding disetujui. Akun telah dipulihkan.';

        $appeal->update([
            'status'      => 'approved',
            'admin_notes' => $adminNotes,
            'resolved_at' => now(),
        ]);

        if ($appeal->user) {
            $this->platformAdminService->activateUser($appeal->user, $adminNotes);
        }

        \App\Services\ActivityLogger::log(
            'approve_suspension_appeal',
            "Menyetujui permohonan banding akun: {$appeal->user->name} ({$appeal->user->email}). Catatan: {$adminNotes}",
            ['appeal_id' => $appeal->id, 'user_id' => $appeal->user_id, 'admin_notes' => $adminNotes]
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
