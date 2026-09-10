<?php

namespace App\Services\AdminSeller;

use App\Models\DigitalProduct;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\Auth;

class DigitalProductService
{
    /**
     * Get a digital product belonging to the authenticated user.
     */
    public function getProduct(int $id, int $userId): DigitalProduct
    {
        return DigitalProduct::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();
    }

    /**
     * Store a new digital product.
     */
    public function storeProduct(array $data, int $userId, $imageFile = null, $platformFile = null): DigitalProduct
    {
        $data['user_id'] = $userId;

        if ($imageFile) {
            $data['image'] = $this->handleImageUpload($imageFile);
        }

        if ($data['platform_type'] === 'upload' && $platformFile) {
            $data['platform_file'] = $this->handleFileUpload($platformFile);
        }

        $createdProduct = DB::transaction(function () use ($data) {
            $product = DigitalProduct::create($data);

            ActivityLogger::log(
                'create_product',
                "Seller " . (Auth::user()->name ?? 'Seller') . " menambahkan produk digital baru: '{$product->title}'.",
                ['product_id' => $product->id, 'title' => $product->title, 'price' => $product->price]
            );

            return $product;
        });

        return $createdProduct;
    }

    /**
     * Update an existing digital product.
     */
    public function updateProduct(DigitalProduct $product, array $data, $imageFile = null, $platformFile = null): DigitalProduct
    {
        if ($data['platform_type'] === 'upload' && $platformFile) {
            if ($product->platform_file) {
                Storage::disk('public')->delete($product->platform_file);
            }
            $data['platform_file'] = $this->handleFileUpload($platformFile);
        } else if ($data['platform_type'] !== 'upload') {
            if ($product->platform_file) {
                Storage::disk('public')->delete($product->platform_file);
                $data['platform_file'] = null;
            }
        }

        if ($imageFile) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $this->handleImageUpload($imageFile);
        }

        if ($product->verification_status === 'rejected') {
            $data['verification_status'] = 'pending';
            $data['rejection_reason'] = null;
        }

        DB::transaction(function () use ($product, $data) {
            $product->update($data);

            ActivityLogger::log(
                'update_product',
                "Seller " . (Auth::user()->name ?? 'Seller') . " memperbarui informasi produk digital: '{$product->title}'.",
                ['product_id' => $product->id, 'title' => $product->title]
            );
        });

        return $product;
    }

    /**
     * Delete a digital product.
     */
    public function deleteProduct(DigitalProduct $product): string
    {
        $productTitle = $product->title;
        $productId = $product->id;

        $msg = DB::transaction(function () use ($product, $productTitle, $productId) {
            if ($product->transactions()->exists()) {
                $product->delete();
                $msg = 'Produk berhasil dihapus (soft delete).';
            } else {
                $product->forceDelete();
                $msg = 'Produk berhasil dihapus secara permanen.';
            }

            ActivityLogger::log(
                'delete_product',
                "Seller " . (Auth::user()->name ?? 'Seller') . " menghapus produk digital: '{$productTitle}'.",
                ['product_id' => $productId, 'title' => $productTitle]
            );

            return $msg;
        });

        return $msg;
    }

    private function handleImageUpload($file): string
    {
        $filename = 'product_images/' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.webp';

        $image = \Intervention\Image\ImageManager::gd()->read($file)
            ->scaleDown(width: 1200);

        $encoded = $image->toWebp(80);

        Storage::disk('public')->put($filename, (string) $encoded);

        return $filename;
    }

    private function handleFileUpload($file): string
    {
        $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        return $file->storeAs('digital_products', $filename, 'public');
    }
}
