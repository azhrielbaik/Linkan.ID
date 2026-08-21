<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DividerElement;
use App\Models\Appearance;

class DividerElementController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:line,space',
            'size' => 'required|integer|min:1|max:200',
            'element_id' => 'nullable|integer'
        ]);

        $user = auth()->user();
        
        $dividerElement = null;
        if ($request->element_id) {
            $dividerElement = DividerElement::where('id', $request->element_id)->where('user_id', $user->id)->first();
        }

        if (!$dividerElement) {
            $dividerElement = new DividerElement();
            $dividerElement->user_id = $user->id;
            $maxOrder = DividerElement::where('user_id', $user->id)->max('order_position');
            $dividerElement->order_position = $maxOrder ? $maxOrder + 1 : 1;
        }

        $dividerElement->type = $request->type;
        $dividerElement->size = $request->size;
        $dividerElement->save();

        return response()->json([
            'success' => true,
            'id' => $dividerElement->id,
            'type' => $dividerElement->type,
            'size' => $dividerElement->size
        ]);
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $element = DividerElement::where('id', $id)->where('user_id', $user->id)->first();
        if ($element) {
            $element->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }
}
