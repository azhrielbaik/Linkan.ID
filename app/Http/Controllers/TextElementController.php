<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TextElementController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'nullable|string',
            'element_id' => 'nullable|integer'
        ]);

        $user = auth()->user();
        
        $textElement = null;
        if ($request->element_id) {
            $textElement = \App\Models\TextElement::where('id', $request->element_id)->where('user_id', $user->id)->first();
        }

        if (!$textElement) {
            $textElement = new \App\Models\TextElement();
            $textElement->user_id = $user->id;
            $maxOrder = \App\Models\TextElement::where('user_id', $user->id)->max('order_position');
            $textElement->order_position = $maxOrder ? $maxOrder + 1 : 1;
        }

        $textElement->content = $request->content;
        $textElement->save();

        return response()->json([
            'success' => true,
            'id' => $textElement->id,
            'content' => $textElement->content
        ]);
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $element = \App\Models\TextElement::where('id', $id)->where('user_id', $user->id)->first();
        if ($element) {
            $element->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }
}
