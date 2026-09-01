<?php

namespace App\Http\Controllers\PlatformAdmin;

use App\Http\Controllers\Controller;
use App\Models\PayoutTransaction;
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

        if ($startDate) {
            if ($endDate) {
                $query->whereDate('created_at', '>=', $startDate)
                      ->whereDate('created_at', '<=', $endDate);
            } else {
                $query->whereDate('created_at', $startDate);
            }
        }

        $payouts = $query->paginate(15)->withQueryString();

        // Ringkasan Statistik
        $totalPendingCount  = PayoutTransaction::where('status', 'pending')->count();
        $totalPendingAmount = PayoutTransaction::where('status', 'pending')->sum('amount');

        $totalApprovedCount  = PayoutTransaction::where('status', 'approved')->count();
        $totalApprovedAmount = PayoutTransaction::where('status', 'approved')->sum('amount');

        $totalRejectedCount = PayoutTransaction::where('status', 'rejected')->count();

        $totalCommissionEarned = DB::table('platform_commissions')->sum('commission');

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
            'totalCommissionEarned'
        ));
    }

    /**
     * Menyetujui (Approve) permohonan penarikan dana.
     */
    public function approve(Request $request, $id)
    {
        $payout = PayoutTransaction::findOrFail($id);

        if ($payout->status !== 'pending') {
            return back()->with('error', 'Permintaan payout ini sudah diproses sebelumnya.');
        }

        $adminId = Auth::id();
        $grossAmount = $payout->gross_amount ?? ($payout->amount / 0.95);
        $commission = $payout->commission > 0 ? $payout->commission : ($grossAmount * 0.05);

        DB::transaction(function () use ($payout, $adminId, $grossAmount, $commission) {
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
        });

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

        $payout = PayoutTransaction::findOrFail($id);

        if ($payout->status !== 'pending') {
            return back()->with('error', 'Permintaan payout ini sudah diproses sebelumnya.');
        }

        $refundAmount = $payout->gross_amount ?? ($payout->amount / 0.95);

        DB::transaction(function () use ($payout, $refundAmount, $request) {
            // Update status payout jadi rejected
            $payout->update([
                'status' => 'rejected',
                'rejection_reason' => $request->input('rejection_reason'),
                'processed_at' => now(),
                'processed_by' => Auth::id(),
            ]);

            // Kembalikan saldo yang di-hold ke seller
            DB::table('users')->where('id', $payout->user_id)->increment('balance', $refundAmount);
        });

        // Catat Log Aktivitas
        \App\Services\ActivityLogger::log(
            'reject_payout',
            "Menolak penarikan dana Rp " . number_format($payout->amount, 0, ',', '.') . " ke {$payout->account_name}. Alasan: {$request->input('rejection_reason')}",
            [
                'payout_id' => $payout->id,
                'seller_id' => $payout->user_id,
                'refund_amount' => $refundAmount,
                'method' => $payout->method,
                'rejection_reason' => $request->input('rejection_reason')
            ]
        );

        return back()->with('success', 'Permintaan penarikan berhasil ditolak. Saldo Rp ' . number_format($refundAmount, 0, ',', '.') . ' telah dikembalikan ke akun seller.');
    }
}
