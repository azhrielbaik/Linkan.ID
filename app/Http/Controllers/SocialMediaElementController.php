<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SocialMediaElement;
use App\Models\Appearance;

class SocialMediaElementController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'platforms' => 'nullable|array',
            'element_id' => 'nullable|integer'
        ]);

        $user = auth()->user();
        
        $element = null;
        if ($request->element_id) {
            $element = SocialMediaElement::where('id', $request->element_id)->where('user_id', $user->id)->first();
        }

        if (!$element) {
            $element = new SocialMediaElement();
            $element->user_id = $user->id;
            $element->is_active = true;
        } 
        
        $element->platforms = $request->platforms ?? [];

        $element->save();

        return response()->json([
            'success' => true,
            'id' => $element->id,
            'element' => $element
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'platforms' => 'nullable|array'
        ]);

        $element = SocialMediaElement::where('user_id', auth()->id())->find($id);
        if ($element) {
            // Process empty inputs from frontend
            $platforms = [];
            if ($request->platforms && is_array($request->platforms)) {
                foreach ($request->platforms as $key => $val) {
                    if (!empty($val)) {
                        $platforms[$key] = $val;
                    }
                }
            }
            
            $element->platforms = $platforms;
            $element->save();

            return response()->json([
                'success' => true,
                'element' => $element
            ]);
        }
        return response()->json(['success' => false], 404);
    }

    public function destroy($id)
    {
        $element = SocialMediaElement::where('user_id', auth()->id())->find($id);
        if ($element) {
            $appearance = Appearance::where('user_id', auth()->id())->first();
            if ($appearance && $appearance->blocks_order) {
                $order = json_decode($appearance->blocks_order, true);
                if (is_array($order)) {
                    $order = array_filter($order, function($item) use ($id) {
                        return $item !== 'social_' . $id;
                    });
                    $appearance->blocks_order = json_encode(array_values($order));
                    $appearance->save();
                }
            }
            $element->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }
}
