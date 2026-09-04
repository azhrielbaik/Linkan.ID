<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VideoElement;
use App\Models\Appearance;

class VideoElementController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'video_url' => 'nullable|string',
            'is_autoplay' => 'nullable',
            'element_id' => 'nullable|integer',
            'appearance_id' => 'required|integer|exists:appearances,id'
        ]);

        $user = auth()->user();
        
        $element = null;
        if ($request->element_id) {
            $element = VideoElement::where('id', $request->element_id)->where('user_id', $user->id)->first();
        }

        if (!$element) {
            $element = new VideoElement();
            $element->user_id = $user->id;
            $element->appearance_id = $request->appearance_id;
            $element->is_active = true;
        }

        $element->video_url = $request->video_url ?? '';
        $element->is_autoplay = filter_var($request->is_autoplay, FILTER_VALIDATE_BOOLEAN);
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
            'video_url' => 'nullable|string|url',
            'is_autoplay' => 'boolean'
        ]);

        $element = VideoElement::where('user_id', auth()->id())->find($id);
        if ($element) {
            $element->video_url = $request->video_url;
            // The frontend might send string 'true'/'false' or boolean 1/0
            $element->is_autoplay = filter_var($request->is_autoplay, FILTER_VALIDATE_BOOLEAN);
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
        $element = VideoElement::where('user_id', auth()->id())->find($id);
        if ($element) {
            // Also need to remove it from blocks_order in Appearance
            $appearance = Appearance::find($element->appearance_id);
            if ($appearance && $appearance->blocks_order) {
                $order = json_decode($appearance->blocks_order, true);
                if (is_array($order)) {
                    $order = array_filter($order, function($item) use ($id) {
                        return $item !== 'videoBlock_' . $id;
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
