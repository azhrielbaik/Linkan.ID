<?php

namespace App\Http\Controllers;

use App\Models\Dispute;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PublicDisputeController extends Controller
{
    /**
     * Tampilkan formulir pengajuan sengketa/komplain pembeli.
     */
    public function create(Request $request)
    {
        $prefilledOrderId = $request->query('order_id', '');
        return view('public.dispute_form', compact('prefilledOrderId'));
    }

    /**
     * Endpoint AJAX untuk memvalidasi nomor Order ID dan email pembeli.
     */
    public function checkOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
            'buyer_email' => 'required|email',
        ]);

        $orderId = trim($request->input('order_id'));
        $email = trim($request->input('buyer_email'));

        $transaction = Transaction::with(['product.user'])
            ->where('order_id', $orderId)
            ->where('buyer_email', $email)
            ->first();

        if (!$transaction) {
            return response()->json([
                'valid' => false,
                'message' => 'Transaksi tidak ditemukan. Pastikan Order ID dan Email Pembeli sesuai dengan yang tercantum di bukti pembelian.'
            ], 404);
        }

        if ($transaction->status->value === 'pending' || $transaction->status->value === 'failed') {
            return response()->json([
                'valid' => false,
                'message' => 'Sengketa hanya dapat diajukan untuk transaksi yang telah berhasil dibayar (Status saat ini: ' . strtoupper($transaction->status->value) . ').'
            ], 422);
        }

        if ($transaction->status->value === 'refunded') {
            return response()->json([
                'valid' => false,
                'message' => 'Transaksi ini sudah selesai diproses refund sebelumnya.'
            ], 422);
        }

        // Cek apakah ada sengketa yang masih aktif/berjalan untuk transaksi ini
        $existingDispute = Dispute::where('transaction_id', $transaction->id)
            ->whereIn('status', ['pending', 'under_review'])
            ->first();

        if ($existingDispute) {
            return response()->json([
                'valid' => false,
                'message' => 'Sengketa untuk pesanan ini sudah terdaftar sebelumnya dengan kode ' . $existingDispute->dispute_code . ' dan sedang dalam investigasi tim Linkan.ID.'
            ], 422);
        }

        return response()->json([
            'valid' => true,
            'data' => [
                'order_id' => $transaction->order_id,
                'product_title' => $transaction->product->title ?? 'Produk Digital',
                'seller_name' => $transaction->product->user->name ?? 'Seller Linkan',
                'buyer_name' => $transaction->buyer_name,
                'buyer_email' => $transaction->buyer_email,
                'total_price' => (float) $transaction->total_price,
                'formatted_price' => 'Rp ' . number_format($transaction->total_price, 0, ',', '.'),
                'created_at' => $transaction->created_at->format('d M Y, H:i'),
            ]
        ]);
    }

    /**
     * Simpan pengajuan komplain sengketa pembeli.
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
            'buyer_email' => 'required|email',
            'buyer_name' => 'required|string|max:100',
            'buyer_phone' => 'nullable|string|max:25',
            'reason' => 'required|in:broken_link,corrupted_file,misleading_description,fraud_scam,other',
            'description' => 'required|string|min:20|max:2000',
            'evidence_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,webp|max:5120',
            'refund_bank_name' => 'required|string|max:50',
            'refund_account_name' => 'required|string|max:100',
            'refund_account_number' => 'required|string|max:50',
        ], [
            'description.min' => 'Mohon jelaskan kendala produk secara detail (minimal 20 karakter).',
            'evidence_file.max' => 'Ukuran berkas bukti maksimal adalah 5MB.',
            'evidence_file.mimes' => 'Format berkas bukti harus berupa gambar (JPG, PNG, WEBP) atau PDF.',
        ]);

        $orderId = trim($request->input('order_id'));
        $email = trim($request->input('buyer_email'));

        $transaction = Transaction::with(['product.user'])
            ->where('order_id', $orderId)
            ->where('buyer_email', $email)
            ->first();

        if (!$transaction) {
            return back()->withInput()->with('error', 'Transaksi tidak ditemukan. Mohon periksa kembali Order ID dan email Anda.');
        }

        if ($transaction->status->value !== 'success' && $transaction->status->value !== 'disputed') {
            return back()->withInput()->with('error', 'Sengketa hanya dapat diajukan untuk transaksi yang berstatus Berhasil/Sukses.');
        }

        $existingDispute = Dispute::where('transaction_id', $transaction->id)
            ->whereIn('status', ['pending', 'under_review'])
            ->first();

        if ($existingDispute) {
            return back()->withInput()->with('error', 'Sengketa untuk transaksi ini sedang aktif diproses (Kode: ' . $existingDispute->dispute_code . ').');
        }

        // Upload berkas bukti pendukung
        $evidencePath = null;
        if ($request->hasFile('evidence_file')) {
            $evidencePath = $request->file('evidence_file')->store('disputes/evidence', 'public');
        }

        // Generate Dispute Code: DSP-YYYYMMDD-XXXX
        $disputeCode = 'DSP-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5));

        $dispute = Dispute::create([
            'dispute_code' => $disputeCode,
            'transaction_id' => $transaction->id,
            'order_id' => $transaction->order_id,
            'seller_id' => $transaction->product->user_id,
            'product_id' => $transaction->product_id,
            'buyer_name' => $request->buyer_name,
            'buyer_email' => $request->buyer_email,
            'buyer_phone' => $request->buyer_phone,
            'amount' => $transaction->total_price,
            'reason' => $request->reason,
            'description' => $request->description,
            'evidence_file' => $evidencePath,
            'refund_bank_name' => $request->refund_bank_name,
            'refund_account_name' => $request->refund_account_name,
            'refund_account_number' => $request->refund_account_number,
            'status' => 'pending',
        ]);

        // Tandai transaksi sebagai disputed
        $transaction->update(['status' => 'disputed']);

        return redirect()->route('public.dispute.create')->with([
            'success' => true,
            'dispute_submitted' => true,
            'dispute_code' => $dispute->dispute_code,
            'amount' => $dispute->amount,
            'order_id' => $dispute->order_id,
        ]);
    }
}
