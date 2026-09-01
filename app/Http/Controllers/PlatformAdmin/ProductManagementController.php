<?php

namespace App\Http\Controllers\PlatformAdmin;

use App\Http\Controllers\Controller;
use App\Models\DigitalProduct;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductManagementController extends Controller
{
    /**
     * Mask email address for privacy when displaying in admin views.
     * Shows first two characters of the local part and masks the rest.
     */
    private function maskEmail(string $email): string
    {
        $parts = explode('@', $email);
        if (count($parts) !== 2) {
            return $email; // fallback if not a valid email
        }
        [$local, $domain] = $parts;
        $maskedLocal = substr($local, 0, 2) . str_repeat('*', max(0, strlen($local) - 2));
        return $maskedLocal . '@' . $domain;
    }
    /**
     * Menampilkan daftar semua produk digital dari seluruh seller dengan filter lengkap.
     */
    public function index(Request $request)
    {
        $query = DigitalProduct::with(['user', 'transactions'])->latest();

        // 1. Filter Search (Judul, Deskripsi, Nama/Email Seller)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // 2. Filter Status Tab (all, active, takedown, pending_verification)
        $tab = $request->input('tab', 'all');
        if ($tab === 'active') {
            $query->where('is_active', true);
        } elseif ($tab === 'takedown') {
            $query->where('is_active', false);
        } elseif ($tab === 'pending') {
            $query->where('verification_status', 'pending');
        } elseif ($tab === 'approved') {
            $query->where('verification_status', 'approved');
        } elseif ($tab === 'rejected') {
            $query->where('verification_status', 'rejected');
        }

        // 3. Filter Seller
        if ($sellerId = $request->input('seller_id')) {
            $query->where('user_id', $sellerId);
        }

        // 4. Filter Kategori / Tipe Platform
        if ($platformType = $request->input('platform_type')) {
            $query->where('platform_type', $platformType);
        }

        // 5. Filter Verifikasi
        if ($verificationStatus = $request->input('verification_status')) {
            $query->where('verification_status', $verificationStatus);
        }

        // 6. Filter Rentang Harga
        if ($minPrice = $request->input('min_price')) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice = $request->input('max_price')) {
            $query->where('price', '<=', $maxPrice);
        }

        // 7. Filter Rentang Tanggal Upload
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

        // 8. Sort By
        $sortBy = $request->input('sort', 'latest');
        if ($sortBy === 'oldest') {
            $query->oldest();
        } elseif ($sortBy === 'price_low') {
            $query->orderBy('price', 'asc');
        } elseif ($sortBy === 'price_high') {
            $query->orderBy('price', 'desc');
        }

        $products = $query->paginate(15)->withQueryString();
        // Mask seller email in each product's user relation for privacy
        $products->getCollection()->each(function ($product) {
            if ($product->relationLoaded('user') && $product->user) {
                $product->user->email = $this->maskEmail($product->user->email);
            }
        });

        // Data Statistik
        $totalProductsCount  = DigitalProduct::count();
        $activeProductsCount = DigitalProduct::where('is_active', true)->count();
        $takedownCount       = DigitalProduct::where('is_active', false)->count();
        $pendingCount        = DigitalProduct::where('verification_status', 'pending')->count();

        // List seller untuk dropdown filter
        $sellers = User::where('role', '!=', 'admin_platform')
            ->whereHas('digitalProducts')
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();
        // Mask email addresses in seller list for privacy
        $sellers->each(function ($seller) {
            $seller->email = $this->maskEmail($seller->email);
        });

        return view('platformadmin.products.index', compact(
            'products',
            'tab',
            'search',
            'sellerId',
            'platformType',
            'verificationStatus',
            'minPrice',
            'maxPrice',
            'startDate',
            'endDate',
            'sortBy',
            'totalProductsCount',
            'activeProductsCount',
            'takedownCount',
            'pendingCount',
            'sellers'
        ));
    }

    /**
     * Takedown / Nonaktifkan produk yang melanggar ketentuan.
     */
    public function takedown(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:1000'
        ], [
            'reason.required' => 'Alasan takedown produk wajib diisi.'
        ]);

        $product = DigitalProduct::with('user')->findOrFail($id);

        $product->update([
            'is_active' => false,
            'takedown_reason' => $request->input('reason'),
            'takedown_at' => now(),
        ]);

        // Catat ke Log Aktivitas
        ActivityLogger::log(
            'takedown_product',
            "Melakukan takedown produk: {$product->title} (Seller: " . ($product->user->name ?? 'User') . "). Alasan: {$request->input('reason')}",
            [
                'product_id' => $product->id,
                'product_title' => $product->title,
                'seller_id' => $product->user_id,
                'reason' => $request->input('reason')
            ]
        );

        return back()->with('success', "Produk \"{$product->title}\" berhasil di-takedown.");
    }

    /**
     * Mengaktifkan kembali (Restore) produk yang telah di-takedown.
     */
    public function restore(Request $request, $id)
    {
        $product = DigitalProduct::with('user')->findOrFail($id);

        $product->update([
            'is_active' => true,
            'takedown_reason' => null,
            'takedown_at' => null,
        ]);

        // Catat ke Log Aktivitas
        ActivityLogger::log(
            'restore_product',
            "Mengaktifkan kembali produk yang di-takedown: {$product->title} (Seller: " . ($product->user->name ?? 'User') . ")",
            [
                'product_id' => $product->id,
                'product_title' => $product->title,
                'seller_id' => $product->user_id
            ]
        );

        return back()->with('success', "Produk \"{$product->title}\" berhasil diaktifkan kembali.");
    }
}
