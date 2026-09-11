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
    public function index(Request $request)
    {
        $status = $request->input('status', 'pending');
        $search = $request->input('search');
        $platformType = $request->input('platform_type');
        $startDate = $request->input('start_date') ?: $request->input('date', '');
        $endDate = $request->input('end_date', '');

        $query = DigitalProduct::with('user')->latest();

        // Filter Status Tab
        if ($status === 'pending') {
            $query->where('verification_status', 'pending');
        } elseif ($status === 'approved') {
            $query->where('verification_status', 'approved');
        } elseif ($status === 'rejected') {
            $query->where('verification_status', 'rejected');
        } elseif ($status === 'archive') {
            $query->whereIn('verification_status', ['approved', 'rejected']);
        } elseif ($status === 'all') {
            // Tampilkan semua tanpa filter status
        } else {
            // Default fallback ke pending
            $status = 'pending';
            $query->where('verification_status', 'pending');
        }

        // Filter Search (Judul, Deskripsi, Nama/Email Seller)
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Tipe Platform
        if ($platformType && in_array($platformType, ['upload', 'dropbox', 'gdrive', 'other'])) {
            $query->where('platform_type', $platformType);
        }

        // Filter Rentang Tanggal
        if ($startDate && $endDate) {
            $query->whereDate('created_at', '>=', $startDate)
                  ->whereDate('created_at', '<=', $endDate);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $products = $query->paginate(15)->withQueryString();

        // Hitung total untuk badge tab
        $pendingCount = DigitalProduct::where('verification_status', 'pending')->count();
        $approvedCount = DigitalProduct::where('verification_status', 'approved')->count();
        $rejectedCount = DigitalProduct::where('verification_status', 'rejected')->count();
        $archiveCount = DigitalProduct::whereIn('verification_status', ['approved', 'rejected'])->count();

        return view('platformadmin.verifikasi', compact(
            'products',
            'status',
            'search',
            'platformType',
            'startDate',
            'endDate',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'archiveCount'
        ));
    }

    public function verify(\App\Http\Requests\PlatformAdmin\VerifikasiRequest $request, $id)
    {

        $product = DigitalProduct::findOrFail($id);
        DB::transaction(function () use ($product, $request) {
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
        });

        return redirect()->back()->with('success', 'Status verifikasi produk berhasil diperbarui');
    }

    public function bulkVerify(BulkVerifikasiRequest $request)
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
