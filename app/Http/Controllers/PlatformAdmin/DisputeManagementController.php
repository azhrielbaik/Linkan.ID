<?php

namespace App\Http\Controllers\PlatformAdmin;

use App\Http\Controllers\Controller;
use App\Models\Dispute;
use App\Models\Transaction;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DisputeManagementController extends Controller
{
    /**
     * Tampilkan halaman daftar sengketa dan refund.
     */
    public function index(Request $request)
    {
        $query = Dispute::with(['transaction', 'product', 'seller', 'resolver'])->latest();

        // Filter Tab Status
        $status = $request->input('status', 'all');
        if (in_array($status, ['pending', 'under_review', 'resolved_refunded', 'resolved_rejected'])) {
            $query->where('status', $status);
        }

        // Pencarian (Kode Sengketa, Order ID, Pembeli, Seller, Produk)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('dispute_code', 'like', "%{$search}%")
                  ->orWhere('order_id', 'like', "%{$search}%")
                  ->orWhere('buyer_name', 'like', "%{$search}%")
                  ->orWhere('buyer_email', 'like', "%{$search}%")
                  ->orWhereHas('seller', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('product', function ($pq) use ($search) {
                      $pq->where('title', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Tanggal
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');
        if ($startDate && $endDate) {
            $query->whereDate('created_at', '>=', $startDate)
                  ->whereDate('created_at', '<=', $endDate);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $disputes = $query->paginate(15)->withQueryString();

        // Metrik Ringkasan Statistik
        $metrics = [
            'total' => Dispute::count(),
            'pending' => Dispute::where('status', 'pending')->count(),
            'under_review' => Dispute::where('status', 'under_review')->count(),
            'refunded' => Dispute::where('status', 'resolved_refunded')->count(),
            'rejected' => Dispute::where('status', 'resolved_rejected')->count(),
            'frozen_amount' => (float) Dispute::whereIn('status', ['pending', 'under_review'])->sum('amount'),
            'refunded_amount' => (float) Dispute::where('status', 'resolved_refunded')->sum('amount'),
        ];

        return view('platformadmin.disputes.index', compact('disputes', 'metrics', 'status'));
    }

    /**
     * Tampilkan detail kasus sengketa untuk investigasi admin.
     */
    public function show($id)
    {
        $dispute = Dispute::with(['transaction', 'product', 'seller', 'resolver'])->findOrFail($id);
        return view('platformadmin.disputes.show', compact('dispute'));
    }

    /**
     * Pindahkan status kasus sengketa ke 'Dalam Investigasi' (under_review).
     */
    public function startReview(Request $request, $id)
    {
        $dispute = Dispute::findOrFail($id);

        if ($dispute->status !== 'pending') {
            return back()->with('error', 'Status sengketa ini sudah dalam tahap pemeriksaan atau telah diselesaikan.');
        }

        $dispute->update([
            'status' => 'under_review',
            'admin_notes' => $request->input('admin_notes', $dispute->admin_notes),
        ]);

        ActivityLogger::log(
            'review_dispute',
            "Admin memeriksa sengketa {$dispute->dispute_code} untuk pesanan {$dispute->order_id}.",
            ['dispute_code' => $dispute->dispute_code, 'order_id' => $dispute->order_id]
        );

        return back()->with('success', 'Status sengketa berhasil diperbarui menjadi "Dalam Investigasi".');
    }

    /**
     * Setujui Refund dan proses pengembalian dana pembeli.
     */
    public function processRefund(Request $request, $id)
    {
        $request->validate([
            'admin_password' => 'required',
            'refund_reference' => 'required|string|max:100',
            'admin_notes' => 'nullable|string|max:1000',
        ], [
            'admin_password.required' => 'Kata sandi admin diperlukan untuk konfirmasi refund.',
            'refund_reference.required' => 'Nomor referensi / bukti transaksi transfer refund wajib diisi.',
        ]);

        // Verifikasi kata sandi admin yang sedang login
        if (!Hash::check($request->admin_password, Auth::user()->password)) {
            return back()->with('error', 'Kata sandi admin salah. Tindakan refund dibatalkan demi keamanan.');
        }

        $dispute = Dispute::with(['transaction', 'seller'])->findOrFail($id);

        if (in_array($dispute->status, ['resolved_refunded', 'resolved_rejected'])) {
            return back()->with('error', 'Kasus sengketa ini sudah ditutup sebelumnya.');
        }

        DB::transaction(function () use ($dispute, $request) {
            $dispute->update([
                'status' => 'resolved_refunded',
                'refund_reference' => $request->refund_reference,
                'admin_notes' => $request->admin_notes,
                'resolved_by' => Auth::id(),
                'resolved_at' => now(),
            ]);

            // Update status transaksi menjadi refunded
            if ($dispute->transaction) {
                $dispute->transaction->update(['status' => 'refunded']);
            }

            // Sinkronkan saldo seller agar bersih dari nominal transaksi yang di-refund
            if ($dispute->seller_id) {
                app(\App\Services\AdminSeller\PayoutService::class)->getPayoutOverview($dispute->seller);
            }

            ActivityLogger::log(
                'approve_dispute_refund',
                "Admin menerbitkan refund untuk sengketa {$dispute->dispute_code} sebesar Rp " . number_format($dispute->amount, 0, ',', '.') . " ke {$dispute->buyer_name} ({$dispute->refund_bank_name} - {$dispute->refund_account_number}). Ref: {$request->refund_reference}",
                [
                    'dispute_code' => $dispute->dispute_code,
                    'order_id' => $dispute->order_id,
                    'amount' => $dispute->amount,
                    'buyer' => $dispute->buyer_name,
                    'refund_ref' => $request->refund_reference,
                ]
            );
        });

        return redirect()->route('platform-admin.disputes.index')->with('success', "Refund untuk sengketa {$dispute->dispute_code} berhasil disetujui dan transaksi telah ditandai sebagai Refunded.");
    }

    /**
     * Tolak pengajuan sengketa pembeli (favor seller) dan lepas penahanan saldo.
     */
    public function rejectDispute(Request $request, $id)
    {
        $request->validate([
            'admin_password' => 'required',
            'admin_notes' => 'required|string|min:10|max:1000',
        ], [
            'admin_password.required' => 'Kata sandi admin diperlukan untuk konfirmasi penolakan sengketa.',
            'admin_notes.required' => 'Alasan penolakan sengketa wajib diisi untuk transparansi.',
        ]);

        if (!Hash::check($request->admin_password, Auth::user()->password)) {
            return back()->with('error', 'Kata sandi admin salah. Tindakan dibatalkan demi keamanan.');
        }

        $dispute = Dispute::with(['transaction', 'seller'])->findOrFail($id);

        if (in_array($dispute->status, ['resolved_refunded', 'resolved_rejected'])) {
            return back()->with('error', 'Kasus sengketa ini sudah ditutup sebelumnya.');
        }

        DB::transaction(function () use ($dispute, $request) {
            $dispute->update([
                'status' => 'resolved_rejected',
                'admin_notes' => $request->admin_notes,
                'resolved_by' => Auth::id(),
                'resolved_at' => now(),
            ]);

            // Kembalikan transaksi menjadi success
            if ($dispute->transaction) {
                $dispute->transaction->update(['status' => 'success']);
            }

            // Sinkronkan saldo seller agar dana yang ditahan kembali dapat ditarik
            if ($dispute->seller_id) {
                app(\App\Services\AdminSeller\PayoutService::class)->getPayoutOverview($dispute->seller);
            }

            ActivityLogger::log(
                'reject_dispute',
                "Admin menolak sengketa {$dispute->dispute_code} untuk pesanan {$dispute->order_id}. Dana dikembalikan ke seller. Alasan: {$request->admin_notes}",
                [
                    'dispute_code' => $dispute->dispute_code,
                    'order_id' => $dispute->order_id,
                    'reason_rejected' => $request->admin_notes,
                ]
            );
        });

        return redirect()->route('platform-admin.disputes.index')->with('success', "Sengketa {$dispute->dispute_code} telah ditolak. Penahanan saldo seller telah dilepaskan.");
    }
}
