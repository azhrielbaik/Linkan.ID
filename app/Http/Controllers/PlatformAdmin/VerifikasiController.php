<?php

namespace App\Http\Controllers\PlatformAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PlatformAdmin\VerifikasiRequest;
use App\Http\Requests\PlatformAdmin\BulkVerifikasiRequest;
use App\Models\DigitalProduct;

class VerifikasiController extends Controller
{
    public function index()
    {
        $products = DigitalProduct::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('platformadmin.verifikasi', compact('products'));
    }

    public function verify(\App\Http\Requests\PlatformAdmin\VerifikasiRequest $request, $id)
    {

        $product = DigitalProduct::findOrFail($id);
        $product->verification_status = $request->status;

        if ($request->status === 'rejected') {
            $product->rejection_reason = $request->rejection_reason;
        } else {
            $product->rejection_reason = null;
        }

        $product->save();

        // Catat Log Aktivitas
        $action = $request->status === 'approved' ? 'approve_product' : 'reject_product';
        $desc = $request->status === 'approved'
            ? "Menyetujui verifikasi produk: {$product->title} (Seller: " . ($product->user->name ?? 'User') . ")"
            : "Menolak verifikasi produk: {$product->title} (Alasan: {$request->rejection_reason})";

        \App\Services\ActivityLogger::log($action, $desc, [
            'product_id' => $product->id,
            'product_title' => $product->title,
            'seller_id' => $product->user_id,
            'status' => $request->status,
            'rejection_reason' => $request->rejection_reason
        ]);

        return redirect()->back()->with('success', 'Status verifikasi produk berhasil diperbarui');
    }

    public function bulkVerify(Request $request)
    {
        // Validation handled by BulkVerifikasiRequest
        $validated = $request->validated();

        $products = DigitalProduct::with('user')
            ->whereIn('id', $validated['product_ids'])
            ->where('verification_status', 'pending')
            ->get();

        DB::transaction(function () use ($products, $validated) {
            foreach ($products as $product) {
                $product->verification_status = $validated['status'];
                $product->rejection_reason = $validated['status'] === 'rejected'
                    ? $validated['rejection_reason']
                    : null;
                $product->save();

                \App\Services\ActivityLogger::log(
                    $validated['status'] === 'approved' ? 'approve_product' : 'reject_product',
                    $validated['status'] === 'approved'
                        ? "Menyetujui verifikasi produk: {$product->title} (Bulk)"
                        : "Menolak verifikasi produk: {$product->title} (Bulk, Alasan: {$validated['rejection_reason']})",
                    [
                        'product_id' => $product->id,
                        'product_title' => $product->title,
                        'seller_id' => $product->user_id,
                        'status' => $validated['status'],
                        'rejection_reason' => $validated['rejection_reason'] ?? null,
                        'bulk_action' => true,
                    ]
                );
            }
        });

        return redirect()->back()->with('success', count($products) . ' produk berhasil diperbarui.');
    }
}
