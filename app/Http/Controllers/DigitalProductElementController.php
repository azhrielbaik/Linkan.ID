<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DigitalProduct;
use App\Models\Appearance;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

class DigitalProductElementController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'element_id' => 'nullable|integer',
        ]);

        $user = $request->user();

        $digitalProduct = null;
        if ($request->element_id) {
            $digitalProduct = DigitalProduct::where('id', $request->element_id)->where('user_id', $user->id)->first();
        }

        $isNew = false;
        if (!$digitalProduct) {
            $digitalProduct = new DigitalProduct();
            $digitalProduct->user_id = $user->id;
            $isNew = true;
            // Since DigitalProduct isn't originally an ordered microsite element in a specific table,
            // its sorting will be managed purely by Appearance::blocks_order via its prefix 'DigitalProduct_'
        }

        $digitalProduct->title = $request->title;
        $digitalProduct->description = $request->description;

        // Handle Pricing
        $digitalProduct->pricing_type = $request->pricing_type ?? 'fixed';
        if ($digitalProduct->pricing_type === 'fixed') {
            $digitalProduct->price = $request->price_fixed ?? 0;
            $digitalProduct->sale_price = null;
        } else {
            $digitalProduct->price = 0; // Or baseline
            $digitalProduct->price_min = $request->price_min;
            $digitalProduct->price_max = $request->price_max;
        }

        // Handle Quantity
        $digitalProduct->quantity_min = $request->quantity_min ?? 1;
        if ($request->has_quantity_limit === 'true' || $request->has_quantity_limit == 1) {
            $digitalProduct->has_quantity_limit = true;
            $digitalProduct->quantity = $request->quantity_max;
        } else {
            $digitalProduct->has_quantity_limit = false;
            $digitalProduct->quantity = null;
        }

        // Handle Scheduling
        if ($request->is_scheduled === 'true' || $request->is_scheduled == 1) {
            $digitalProduct->is_scheduled = true;
            $digitalProduct->start_time = $request->start_time ? \Carbon\Carbon::parse($request->start_time) : null;
            $digitalProduct->end_time = $request->end_time ? \Carbon\Carbon::parse($request->end_time) : null;
        } else {
            $digitalProduct->is_scheduled = false;
            $digitalProduct->start_time = null;
            $digitalProduct->end_time = null;
        }

        // Handle Deliverable
        $digitalProduct->deliverable_type = $request->deliverable_type; // 'upload', 'gdrive', 'other'
        if ($digitalProduct->deliverable_type === 'upload') {
            if ($request->hasFile('deliverable_file')) {
                // Delete old file if exists
                if ($digitalProduct->deliverable_url) Storage::disk('public')->delete($digitalProduct->deliverable_url);

                $file = $request->file('deliverable_file');
                $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('digital_products/deliverables', $filename, 'public');
                $digitalProduct->deliverable_url = $filePath;
            } else if ($request->has('remove_deliverable_file') && $request->remove_deliverable_file == 1) {
                if ($digitalProduct->deliverable_url) Storage::disk('public')->delete($digitalProduct->deliverable_url);
                $digitalProduct->deliverable_url = null;
            }
        } else if ($request->deliverable_type !== 'upload') {
            $digitalProduct->deliverable_url = $request->deliverable_url;
        }

        try {
            // Handle Media Files (Array of files)
            $oldMediaFiles = $digitalProduct->media_files;
            if (!is_array($oldMediaFiles)) {
                $oldMediaFiles = !empty($oldMediaFiles) ? json_decode($oldMediaFiles, true) : [];
            }
            $oldMediaFiles = $oldMediaFiles ?? [];

            // Fallback jika sebelumnya produk belum memiliki array media_files tapi sudah punya kolom image
            if (empty($oldMediaFiles) && !empty($digitalProduct->image)) {
                $oldMediaFiles = [
                    [
                        'url'  => $digitalProduct->image,
                        'type' => 'image/webp',
                        'path' => $digitalProduct->image,
                    ]
                ];
            }

            $mediaFiles = [];

            $normalizePath = function(?string $u): string {
                if (!$u) return '';
                $u = str_replace(url('/storage') . '/', '', $u);
                $u = str_replace(asset('storage') . '/', '', $u);
                $u = ltrim($u, '/');
                if (str_starts_with($u, 'storage/')) {
                    $u = substr($u, 8);
                }
                return $u;
            };

            if ($request->has('existing_media')) {
                $existingMedia = json_decode($request->existing_media, true);
                if (is_array($existingMedia)) {
                    $keptUrls = array_map($normalizePath, array_column($existingMedia, 'url'));
                    foreach ($oldMediaFiles as $old) {
                        $oldNorm = $normalizePath($old['url'] ?? $old['path'] ?? '');
                        if (in_array($oldNorm, $keptUrls)) {
                            $mediaFiles[] = $old;
                        } else if (!empty($old['path'])) {
                            Storage::disk('public')->delete($old['path']);
                        }
                    }
                }
            }

            if ($request->has('media_count')) {
                $count = (int)$request->media_count;
                for ($i = 0; $i < $count; $i++) {
                    if ($request->hasFile("media_$i")) {
                        $file = $request->file("media_$i");
                        $mime = $file->getMimeType();

                        if (str_starts_with($mime, 'image/')) {
                            $filename = 'digital_products/media/' . time() . '_' . Str::random(10) . '.webp';
                            $image = ImageManager::gd()->read($file)->scaleDown(width: 1200);
                            $encoded = $image->toWebp(80);
                            Storage::disk('public')->put($filename, (string) $encoded);
                            $mediaFiles[] = ['url' => $filename, 'type' => $mime, 'path' => $filename];
                        } else if (str_starts_with($mime, 'video/')) {
                            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                            $filePath = $file->storeAs('digital_products/media', $filename, 'public');
                            $mediaFiles[] = ['url' => $filePath, 'type' => $mime, 'path' => $filePath];
                        }
                    }
                }
            }

            $digitalProduct->media_files = $mediaFiles;

            // Ensure image has the first media image file for fallback in other views
            if (count($mediaFiles) > 0) {
                $firstImage = collect($mediaFiles)->first(fn($m) => isset($m['type']) && str_starts_with($m['type'], 'image/'));
                if ($firstImage) {
                    $digitalProduct->image = $firstImage['path'] ?? $firstImage['url'];
                }
            } else {
                $digitalProduct->image = null;
            }

            $digitalProduct->button_text = 'Beli Sekarang';
            $digitalProduct->platform_type = 'other'; // Compatibility with old column

            $digitalProduct->save();

            if ($isNew && $request->has('appearance_id')) {
                $appearance = Appearance::where('id', $request->appearance_id)->where('user_id', $user->id)->first();
                if ($appearance) {
                    $order = $appearance->blocks_order ? explode(',', $appearance->blocks_order) : [];
                    $key = 'digitalproduct_' . $digitalProduct->id;
                    if (!in_array($key, $order)) {
                        $order[] = $key;
                        $appearance->blocks_order = implode(',', $order);
                        $appearance->save();
                    }
                }
            }

            return response()->json([
                'success' => true,
                'id' => $digitalProduct->id,
                'title' => $digitalProduct->title
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Gagal menyimpan produk digital element: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus (unpin) produk digital dari microsite tertentu.
     * Produk digital tetap tersimpan di database/halaman Toko dan microsite lainnya.
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $product = DigitalProduct::where('id', $id)->where('user_id', $user->id)->first();
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan.'
            ], 404);
        }

        $appearanceId = $request->input('appearance_id') ?? $request->query('appearance_id');
        $key = 'digitalproduct_' . $product->id;

        if ($appearanceId) {
            $appearance = Appearance::where('id', $appearanceId)
                ->where('user_id', $user->id)
                ->first();

            if ($appearance && $appearance->blocks_order) {
                $order = array_filter(
                    explode(',', $appearance->blocks_order),
                    fn($b) => trim($b) !== $key
                );
                $appearance->blocks_order = implode(',', array_values($order));
                $appearance->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus dari microsite ini.'
        ]);
    }

    /**
     * Tambahkan produk yang SUDAH ADA ke blocks_order microsite (tanpa membuat produk baru).
     * Dipanggil dari fitur "Pilih dari Toko" di microsite editor.
     */
    public function pinExisting(Request $request)
    {
        $request->validate([
            'product_id'    => 'required|integer',
            'appearance_id' => 'required|integer',
        ]);

        $user = $request->user();

        // Pastikan produk milik user ini
        $product = DigitalProduct::where('id', $request->product_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Pastikan appearance milik user ini
        $appearance = Appearance::where('id', $request->appearance_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $key   = 'digitalproduct_' . $product->id;
        $order = $appearance->blocks_order ? array_filter(explode(',', $appearance->blocks_order)) : [];

        // Jangan tambahkan duplikat
        if (in_array($key, $order)) {
            return response()->json([
                'success' => false,
                'message' => 'Produk ini sudah ada di microsite Anda.',
            ], 409);
        }

        $order[] = $key;
        $appearance->blocks_order = implode(',', $order);
        $appearance->save();

        return response()->json([
            'success' => true,
            'id'      => $product->id,
            'title'   => $product->title,
        ]);
    }
}
