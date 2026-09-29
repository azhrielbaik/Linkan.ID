<?php

namespace App\Http\Controllers;

use App\Helpers\ReviewTokenHelper;
use App\Models\ProductReview;
use App\Models\Transaction;
use Illuminate\Http\Request;

class PublicReviewController extends Controller
{
    /**
     * Tampilkan halaman form review (diakses dari link di email).
     * Route: GET /{locale}/review/{token}
     */
    public function show(string $token)
    {
        $orderId = ReviewTokenHelper::verify($token);
        if (!$orderId) {
            abort(403, 'Link ulasan tidak valid atau sudah kadaluarsa.');
        }

        // Cek apakah transaksi valid dan sudah berhasil
        $transaction = Transaction::where('order_id', $orderId)
            ->where(function ($query) {
                $query->where('status', 'success')
                      ->orWhere('status', 'completed');
            })
            ->with('product') // eager load produk
            ->first();

        if (!$transaction) {
            abort(404, 'Transaksi tidak ditemukan atau belum berstatus sukses.');
        }

        // Cek apakah sudah pernah review (token hanya bisa dipakai sekali)
        $existingReview = ProductReview::where('order_id', $orderId)->first();
        $alreadyReviewed = (bool) $existingReview;

        return view('public.review', compact('transaction', 'token', 'alreadyReviewed', 'existingReview'));
    }

    /**
     * Simpan review yang dikirimkan buyer.
     * Route: POST /{locale}/review/{token}
     */
    public function store(Request $request, string $token)
    {
        $orderId = ReviewTokenHelper::verify($token);
        if (!$orderId) {
            return back()->withErrors(['token' => 'Link ulasan tidak valid atau tanda tangan digital rusak.']);
        }

        $transaction = Transaction::where('order_id', $orderId)
            ->where(function ($query) {
                $query->where('status', 'success')
                      ->orWhere('status', 'completed');
            })
            ->firstOrFail();

        // Pastikan belum pernah review — cegah double submit
        if (ProductReview::where('order_id', $orderId)->exists()) {
            return back()->with('already_reviewed', true);
        }

        $request->validate([
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:500'],
        ], [
            'rating.required' => 'Silakan pilih rating bintang terlebih dahulu (1 - 5 bintang).',
            'rating.min'      => 'Rating minimal adalah 1 bintang.',
            'rating.max'      => 'Rating maksimal adalah 5 bintang.',
            'comment.max'     => 'Panjang ulasan tidak boleh melebihi 500 karakter.',
        ]);

        // Samarkan nama buyer: "Budi Santoso" → "Budi S."
        $trimmedName = trim($transaction->buyer_name ?: 'Pembeli');
        $nameParts = preg_split('/\s+/', $trimmedName);
        $maskedName = $nameParts[0];
        if (count($nameParts) > 1) {
            $lastPart = end($nameParts);
            $maskedName .= ' ' . strtoupper(mb_substr($lastPart, 0, 1)) . '.';
        }

        ProductReview::create([
            'product_id'  => $transaction->product_id,
            'order_id'    => $orderId,
            'buyer_name'  => $maskedName,
            'buyer_email' => $transaction->buyer_email,
            'rating'      => (int) $request->rating,
            'comment'     => $request->filled('comment') ? trim($request->comment) : null,
            'is_visible'  => true,
        ]);

        return redirect()->back()->with('review_success', true);
    }
}
