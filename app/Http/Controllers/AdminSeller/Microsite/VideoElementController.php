<?php

namespace App\Http\Controllers\AdminSeller\Microsite;

use App\Http\Controllers\Controller;
use App\Models\Appearance;
use App\Models\VideoElement;
use Illuminate\Http\Request;

class VideoElementController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'video_url' => 'nullable|string',
            'is_autoplay' => 'nullable',
            'element_id' => 'nullable|integer',
            'appearance_id' => 'required|integer|exists:appearances,id',
        ]);

        $user = auth()->user();

        $element = null;
        if ($request->element_id) {
            $element = VideoElement::where('id', $request->element_id)->where('user_id', $user->id)->first();
        }

        if (! $element) {
            $element = new VideoElement;
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
            'element' => $element,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'video_url' => 'nullable|string|url',
            'is_autoplay' => 'boolean',
        ]);

        $element = VideoElement::where('user_id', auth()->id())->find($id);
        if ($element) {
            $element->video_url = $request->video_url;
            $element->is_autoplay = filter_var($request->is_autoplay, FILTER_VALIDATE_BOOLEAN);
            $element->save();

            return response()->json([
                'success' => true,
                'element' => $element,
            ]);
        }

        return response()->json(['success' => false], 404);
    }

    public function destroy($id)
    {
        $element = VideoElement::where('user_id', auth()->id())->find($id);
        if ($element) {
            $appearance = Appearance::find($element->appearance_id);
            if ($appearance) {
                $appearance->removeBlock('videoBlock_'.$id);
                $appearance->removeBlock('video_'.$id);
            }
            $element->delete();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }
}
