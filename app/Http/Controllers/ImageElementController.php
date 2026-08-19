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
            'element_id' => 'nullable|integer'
        ]);

        $user = auth()->user();
        
        $imageElement = null;
        if ($request->element_id) {
            $imageElement = ImageElement::where('id', $request->element_id)->where('user_id', $user->id)->first();
        }

        if (!$imageElement) {
            $imageElement = new ImageElement();
            $imageElement->user_id = $user->id;
            $maxOrder = ImageElement::where('user_id', $user->id)->max('order_position');
            $imageElement->order_position = $maxOrder ? $maxOrder + 1 : 1;
        }

        if ($request->hasFile('image')) {
            if ($imageElement->image_path) {
                Storage::disk('public')->delete($imageElement->image_path);
            }
            $path = $request->file('image')->store('elements/images', 'public');
            $imageElement->image_path = $path;
        }

        $imageElement->link_url = $request->link_url;
        $imageElement->save();

        return response()->json([
            'success' => true,
            'id' => $imageElement->id,
            'image_url' => $imageElement->image_path ? asset('storage/' . $imageElement->image_path) : null
        ]);
    }

    public function destroy($id)
    {
        $user = auth()->user();
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
            'blocks_order' => 'required|string'
        ]);

        $user = auth()->user();
        $appearance = Appearance::firstOrCreate(
            ['user_id' => $user->id],
            ['name' => $user->name ?? 'My Linkan']
        );

        $appearance->blocks_order = $request->blocks_order;
        $appearance->save();

        return response()->json(['success' => true]);
    }
}
