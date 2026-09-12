<?php

namespace App\Http\Controllers\PlatformAdmin;

use App\Http\Controllers\Controller;
use App\Models\PayoutTransaction;
use App\Models\PlatformSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PayoutManagementController extends Controller
{
    /**
     * Menampilkan daftar pengajuan penarikan dana dan riwayat payout global.
     */
    public function index(Request $request)
    {
        $query = PayoutTransaction::with(['user', 'processor'])->latest();

        // Filter Tab Status
        $tab = $request->input('tab', 'all');
        if (in_array($tab, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $tab);
        }

        // Filter Pencarian (Seller name, email, account_name, account_number)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('account_name', 'like', "%{$search}%")
                  ->orWhere('account_number', 'like', "%{$search}%")
                  ->orWhere('method', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Metode Pembayaran
        if ($method = $request->input('method')) {
            $query->where('method', $method);
        }

        // Filter Tanggal
        $startDate = $request->input('start_date') ?: $request->input('date', '');
        $endDate   = $request->input('end_date', '');

        if ($startDate && $endDate) {
            $query->whereDate('created_at', '>=', $startDate)
                  ->whereDate('created_at', '<=', $endDate);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $payouts = $query->paginate(15)->withQueryString();

        // Ringkasan Statistik
        $totalPendingCount  = PayoutTransaction::where('status', 'pending')->count();
        $totalPendingAmount = PayoutTransaction::where('status', 'pending')->sum('amount');

        $totalApprovedCount  = PayoutTransaction::where('status', 'approved')->count();
        $totalApprovedAmount = PayoutTransaction::where('status', 'approved')->sum('amount');

        $totalRejectedCount = PayoutTransaction::where('status', 'rejected')->count();

        $totalCommissionEarned = DB::table('platform_commissions')->sum('commission');
        $commissionPercent = (float) PlatformSetting::get('platform_commission_percent', 5);

        return view('platformadmin.payouts', compact(
            'payouts',
            'tab',
            'search',
            'method',
            'startDate',
            'endDate',
            'totalPendingCount',
            'totalPendingAmount',
            'totalApprovedCount',
            'totalApprovedAmount',
            'totalRejectedCount',
            'totalCommissionEarned',
            'commissionPercent'
        ));
    }

    /**
     * Menyetujui (Approve) permohonan penarikan dana.
     */
    public function approve(Request $request, $id)
    {
        $adminId = Auth::id();

        try {
            $payoutData = DB::transaction(function () use ($id, $adminId) {
                // Kunci baris payout secara eksklusif (SELECT ... FOR UPDATE) untuk mencegah race condition / double approval
                $payout = PayoutTransaction::where('id', $id)->lockForUpdate()->first();

                if (!$payout) {
                    throw new \Exception('Permintaan payout tidak ditemukan.');
                }

                if ($payout->status !== 'pending') {
                    throw new \Exception('Permintaan payout ini sudah diproses sebelumnya.');
                }

                $commissionPercent = (float) PlatformSetting::get('platform_commission_percent', 5);
                $grossAmount = $payout->gross_amount ?? ($commissionPercent < 100 ? ($payout->amount / (1 - ($commissionPercent / 100))) : $payout->amount);
                $commission = $payout->commission > 0 ? $payout->commission : ($grossAmount * ($commissionPercent / 100));

                // Update status payout
                $payout->update([
                    'status' => 'approved',
                    'processed_at' => now(),
                    'processed_by' => $adminId,
                ]);

                // Catat komisi ke tabel platform_commissions
                DB::table('platform_commissions')->insert([
                    'seller_id' => $payout->user_id,
                    'platform_admin_id' => $adminId,
                    'amount' => $grossAmount,
                    'commission' => $commission,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Tambahkan komisi ke saldo admin platform
                DB::table('users')->where('id', $adminId)->increment('balance', $commission);

                return [
                    'payout' => $payout,
                    'gross_amount' => $grossAmount,
                    'commission' => $commission,
                ];
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        $payout = $payoutData['payout'];
        $grossAmount = $payoutData['gross_amount'];
        $commission = $payoutData['commission'];

        // Catat Log Aktivitas
        \App\Services\ActivityLogger::log(
            'approve_payout',
            "Menyetujui penarikan dana Rp " . number_format($payout->amount, 0, ',', '.') . " ke {$payout->account_name} ({$payout->method})",
            [
                'payout_id' => $payout->id,
                'seller_id' => $payout->user_id,
                'net_amount' => $payout->amount,
                'gross_amount' => $grossAmount,
                'commission' => $commission,
                'method' => $payout->method,
                'account_name' => $payout->account_name,
                'account_number' => $payout->account_number
            ]
        );

        \App\Services\PlatformAdminService::clearNotificationsCache();

        return back()->with('success', 'Permintaan penarikan sebesar Rp ' . number_format($payout->amount, 0, ',', '.') . ' berhasil disetujui.');
    }

    /**
     * Menolak (Reject) permohonan penarikan dana & mengembalikan saldo ke seller.
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $adminId = Auth::id();
        $reason = $request->input('rejection_reason');

        try {
            $payoutData = DB::transaction(function () use ($id, $adminId, $reason) {
                // Kunci baris payout secara eksklusif (SELECT ... FOR UPDATE) untuk mencegah race condition / double refund
                $payout = PayoutTransaction::where('id', $id)->lockForUpdate()->first();

                if (!$payout) {
                    throw new \Exception('Permintaan payout tidak ditemukan.');
                }

                if ($payout->status !== 'pending') {
                    throw new \Exception('Permintaan payout ini sudah diproses sebelumnya.');
                }

                $commissionPercent = (float) PlatformSetting::get('platform_commission_percent', 5);
                $refundAmount = $payout->gross_amount ?? ($commissionPercent < 100 ? ($payout->amount / (1 - ($commissionPercent / 100))) : $payout->amount);

                // Update status payout jadi rejected
                $payout->update([
                    'status' => 'rejected',
                    'rejection_reason' => $reason,
                    'processed_at' => now(),
                    'processed_by' => $adminId,
                ]);

                // Kembalikan saldo yang di-hold ke seller
                DB::table('users')->where('id', $payout->user_id)->increment('balance', $refundAmount);

                return [
                    'payout' => $payout,
                    'refund_amount' => $refundAmount,
                ];
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        $payout = $payoutData['payout'];
        $refundAmount = $payoutData['refund_amount'];

        // Catat Log Aktivitas
        \App\Services\ActivityLogger::log(
            'reject_payout',
            "Menolak penarikan dana Rp " . number_format($payout->amount, 0, ',', '.') . " ke {$payout->account_name}. Alasan: {$reason}",
            [
                'payout_id' => $payout->id,
                'seller_id' => $payout->user_id,
                'refund_amount' => $refundAmount,
                'method' => $payout->method,
                'rejection_reason' => $reason
            ]
        );

        \App\Services\PlatformAdminService::clearNotificationsCache();

        return back()->with('success', 'Permintaan penarikan berhasil ditolak. Saldo Rp ' . number_format($refundAmount, 0, ',', '.') . ' telah dikembalikan ke akun seller.');
    }
}
