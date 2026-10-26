<?php

namespace App\Http\Controllers\PlatformAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlatformAdmin\ActivateUserRequest;
use App\Http\Requests\PlatformAdmin\ApproveAppealRequest;
use App\Http\Resources\PlatformAdmin\SellerDetailResource;
use App\Models\SuspensionAppeal;
use App\Models\User;
use App\Services\ActivityLogger;
use App\Services\PlatformAdminService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserManagementController extends Controller
{
    protected PlatformAdminService $platformAdminService;

    public function __construct(PlatformAdminService $platformAdminService)
    {
        $this->platformAdminService = $platformAdminService;
    }

    public function users(Request $request)
    {
        $search = $request->input('search');
        $filter = $request->input('filter', 'all');
        $startDate = $request->input('start_date') ?: $request->input('date', '');
        $endDate = $request->input('end_date', '');
        $appealStatus = $request->input('appeal_status');

        $data = $this->platformAdminService->getUsersList($search, $filter, $startDate, $endDate, $appealStatus);

        return view('platformadmin.users', array_merge($data, [
            'filter' => $filter,
            'search' => $search,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]));
    }

    public function appeals(Request $request)
    {
        $request->merge(['view' => 'appeals']);

        return $this->users($request);
    }

    public function userSuggest(Request $request)
    {
        $q = trim($request->query('q', ''));
        if (mb_strlen($q) < 2 || mb_strlen($q) > 100) {
            return response()->json([]);
        }

        return response()->json($this->platformAdminService->getSuggestions($q));
    }

    public function suspend(\App\Http\Requests\PlatformAdmin\SuspendUserRequest $request, int $id)
    {
        $user = User::findOrFail($id);

        if ($user->role === PlatformAdminService::ROLE_PLATFORM_ADMIN) {
            return back()->with('error', 'Tidak dapat men-suspend akun Platform Admin.');
        }

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

        if (! $user->isSuspended()) {
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
            'status' => 'approved',
            'admin_notes' => $adminNotes,
            'resolved_at' => now(),
        ]);

        if ($appeal->user) {
            $this->platformAdminService->activateUser($appeal->user, $adminNotes);
        }

        ActivityLogger::log(
            'approve_suspension_appeal',
            "Menyetujui permohonan banding akun: {$appeal->user->name} ({$appeal->user->email}). Catatan: {$adminNotes}",
            ['appeal_id' => $appeal->id, 'user_id' => $appeal->user_id, 'admin_notes' => $adminNotes]
        );

        return back()->with('success', "Permohonan banding dari {$appeal->user->name} berhasil disetujui dan akun telah dipulihkan.");
    }

    public function rejectAppeal(\App\Http\Requests\PlatformAdmin\RejectAppealRequest $request, int $id)
    {
        $appeal = SuspensionAppeal::with('user')->findOrFail($id);

        $adminNotes = strip_tags($request->admin_notes);

        DB::transaction(function () use ($appeal, $adminNotes) {
            $appeal->update([
                'status' => 'rejected',
                'admin_notes' => $adminNotes,
                'resolved_at' => now(),
            ]);

            ActivityLogger::log(
                'reject_suspension_appeal',
                "Menolak permohonan banding akun: {$appeal->user->name}. Catatan: {$adminNotes}",
                ['appeal_id' => $appeal->id, 'user_id' => $appeal->user_id, 'admin_notes' => $adminNotes]
            );
        });

        PlatformAdminService::clearNotificationsCache();

        return back()->with('success', "Permohonan banding dari {$appeal->user->name} telah ditolak.");
    }

    public function sellerDetail(int $id)
    {
        try {
            $user = User::with('digitalProducts')->findOrFail($id);

            return response()->json(new SellerDetailResource($user));
        } catch (\Exception $e) {
            Log::error('Error loading seller detail: '.$e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
