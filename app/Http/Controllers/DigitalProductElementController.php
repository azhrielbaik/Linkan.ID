<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DigitalProduct;
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
            'appearance_id' => 'required|integer|exists:appearances,id'
        ]);

        $user = $request->user();

        $digitalProduct = null;
        if ($request->element_id) {
            $digitalProduct = DigitalProduct::where('id', $request->element_id)->where('user_id', $user->id)->first();
        }

        if (!$digitalProduct) {
            $digitalProduct = new DigitalProduct();
            $digitalProduct->user_id = $user->id;
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

        // Handle Media Files (Array of files)
        $oldMediaFiles = $digitalProduct->media_files ?? [];
        $mediaFiles = [];

        if ($request->has('existing_media')) {
            $existingMedia = json_decode($request->existing_media, true);
            if (is_array($existingMedia)) {
                $keptUrls = array_column($existingMedia, 'url');
                foreach ($oldMediaFiles as $old) {
                    if (in_array($old['url'], $keptUrls)) {
                        $mediaFiles[] = $old;
                    } else if (isset($old['path'])) {
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

        // Ensure image has the first media file for fallback in other views
        if (count($mediaFiles) > 0 && empty($digitalProduct->image) && str_starts_with($mediaFiles[0]['type'], 'image/')) {
            $digitalProduct->image = str_replace('digital_products/media/', 'product_images/', $mediaFiles[0]['path']);
            // Just saving the path to image column for compatibility if needed
        } else if (count($mediaFiles) === 0) {
            $digitalProduct->image = null;
        }

        $digitalProduct->button_text = 'Beli Sekarang';
        $digitalProduct->platform_type = 'other'; // Compatibility with old column

        $digitalProduct->save();

        return response()->json([
            'success' => true,
            'id' => $digitalProduct->id,
            'title' => $digitalProduct->title
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $element = DigitalProduct::where('id', $id)->where('user_id', $user->id)->first();
        if ($element) {
            $appearances = \App\Models\Appearance::where('user_id', $user->id)->get();
            foreach ($appearances as $appearance) {
                if ($appearance->blocks_order) {
                    $order = is_string($appearance->blocks_order) ? explode(',', $appearance->blocks_order) : $appearance->blocks_order;
                    if (is_array($order)) {
                        $order = array_filter($order, function($item) use ($id) {
                            return $item !== 'DigitalProduct_' . $id;
                        });
                        $appearance->blocks_order = is_string($appearance->blocks_order) ? implode(',', $order) : array_values($order);
                        $appearance->save();
                    }
                }
            }
            
            // Cleanup media if necessary
            if ($element->media_files) {
                foreach ($element->media_files as $media) {
                    if (isset($media['path'])) Storage::disk('public')->delete($media['path']);
                }
            }
            if ($element->deliverable_type === 'upload' && $element->deliverable_url) {
                Storage::disk('public')->delete($element->deliverable_url);
            }
            $element->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }
}
