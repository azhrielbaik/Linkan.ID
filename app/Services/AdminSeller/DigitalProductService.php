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
    public function storeProduct(array $data, int $userId, $imageFiles = null, $platformFile = null, $existingMedia = null): DigitalProduct
    {
        $data['user_id'] = $userId;

        $mediaFiles = [];

        // Normalize imageFiles to array
        $uploadedImages = [];
        if ($imageFiles instanceof \Illuminate\Http\UploadedFile) {
            $uploadedImages = [$imageFiles];
        } elseif (is_array($imageFiles)) {
            $uploadedImages = $imageFiles;
        }

        foreach ($uploadedImages as $file) {
            if ($file instanceof \Illuminate\Http\UploadedFile && $file->isValid()) {
                $path = $this->handleImageUpload($file);
                $mediaFiles[] = [
                    'url'  => $path,
                    'path' => $path,
                    'type' => $file->getMimeType() ?: 'image/jpeg',
                ];
            }
        }

        if (count($mediaFiles) > 0) {
            $data['media_files'] = $mediaFiles;
            $data['image'] = $mediaFiles[0]['path'];
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
    public function updateProduct(DigitalProduct $product, array $data, $imageFiles = null, $platformFile = null, $existingMedia = null): DigitalProduct
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

        // Handle media_files & existing photos
        $oldMediaFiles = is_string($product->media_files) ? json_decode($product->media_files, true) : ($product->media_files ?? []);
        if (!is_array($oldMediaFiles)) {
            $oldMediaFiles = [];
        }
        // If old media_files was empty but product has image, treat that image as an old media item
        if (empty($oldMediaFiles) && !empty($product->image)) {
            $oldMediaFiles[] = [
                'url'  => $product->image,
                'path' => $product->image,
                'type' => 'image/jpeg',
            ];
        }

        $mediaFiles = [];

        // Check which existing media were kept
        if ($existingMedia !== null) {
            $keptExisting = is_string($existingMedia) ? json_decode($existingMedia, true) : $existingMedia;
            if (is_array($keptExisting)) {
                $keptPaths = array_map(function($item) {
                    return is_array($item) ? ($item['path'] ?? $item['url'] ?? '') : (string)$item;
                }, $keptExisting);

                foreach ($oldMediaFiles as $old) {
                    $oldPath = $old['path'] ?? $old['url'] ?? '';
                    if (in_array($oldPath, $keptPaths)) {
                        $mediaFiles[] = $old;
                    } else if (!empty($old['path'])) {
                        Storage::disk('public')->delete($old['path']);
                    }
                }
            } else {
                // If existingMedia was explicitly sent as empty, delete all old files
                foreach ($oldMediaFiles as $old) {
                    if (!empty($old['path'])) {
                        Storage::disk('public')->delete($old['path']);
                    }
                }
            }
        } else {
            $mediaFiles = $oldMediaFiles;
        }

        // Upload any new images
        $uploadedImages = [];
        if ($imageFiles instanceof \Illuminate\Http\UploadedFile) {
            $uploadedImages = [$imageFiles];
        } elseif (is_array($imageFiles)) {
            $uploadedImages = $imageFiles;
        }

        foreach ($uploadedImages as $file) {
            if ($file instanceof \Illuminate\Http\UploadedFile && $file->isValid()) {
                $path = $this->handleImageUpload($file);
                $mediaFiles[] = [
                    'url'  => $path,
                    'path' => $path,
                    'type' => $file->getMimeType() ?: 'image/jpeg',
                ];
            }
        }

        $data['media_files'] = $mediaFiles;
        $data['image'] = count($mediaFiles) > 0 ? $mediaFiles[0]['path'] : null;

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
