<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImageElement;
use App\Models\Appearance;
use Illuminate\Support\Facades\Storage;

class ImageElementController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|max:2048',
            'link_url' => 'nullable|url',
            'element_id' => 'nullable|integer',
            'appearance_id' => 'required|integer|exists:appearances,id'
        ]);

        $user = $request->user();

        $imageElement = null;
        if ($request->element_id) {
            $imageElement = ImageElement::where('id', $request->element_id)->where('user_id', $user->id)->first();
        }

        if (!$imageElement) {
            $imageElement = new ImageElement();
            $imageElement->user_id = $user->id;
            $imageElement->appearance_id = $request->appearance_id;
            $maxOrder = ImageElement::where('appearance_id', $request->appearance_id)->max('order_position');
            $imageElement->order_position = $maxOrder ? $maxOrder + 1 : 1;
        }

        if ($request->hasFile('image')) {
            if ($imageElement->image_path) {
                Storage::disk('public')->delete($imageElement->image_path);
            }

            $file = $request->file('image');
            $filename = 'elements/images/' . time() . '_' . \Illuminate\Support\Str::random(10) . '.webp';

            // Sintaks Intervention Image v4
            $image = \Intervention\Image\ImageManager::gd()->read($file)
                ->scaleDown(width: 1200);

            $encoded = $image->toWebp(80);

            Storage::disk('public')->put($filename, (string) $encoded);

            $imageElement->image_path = $filename;
        }

        $imageElement->link_url = $request->link_url;
        $imageElement->save();

        return response()->json([
            'success' => true,
            'id' => $imageElement->id,
            'image_url' => $imageElement->image_path ? asset('storage/' . $imageElement->image_path) : null
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $element = ImageElement::where('id', $id)->where('user_id', $user->id)->first();
        if ($element) {
            if ($element->image_path) {
                Storage::disk('public')->delete($element->image_path);
            }
            $element->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'blocks_order' => 'required|string',
            'appearance_id' => 'required|integer|exists:appearances,id'
        ]);

        $user = auth()->user();
        $appearance = Appearance::where('user_id', $user->id)->findOrFail($request->appearance_id);

        $appearance->blocks_order = $request->blocks_order;
        \Illuminate\Support\Facades\Log::info('Saving blocks_order: ' . $request->blocks_order);
        $appearance->save();

        return response()->json(['success' => true]);
    }
}
