<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImageElement;
use App\Models\DividerElement;
use App\Models\TextElement;
use App\Models\VideoElement;
use Illuminate\Support\Facades\Auth;

class ElementVisibilityController extends Controller
{
    public function toggle(Request $request)
    {
        $request->validate([
            'element_type' => 'required|string|in:image,divider,text,video,social',
            'element_id' => 'required|integer',
            'is_active' => 'required|boolean'
        ]);

        $userId = Auth::id();
        $type = $request->element_type;
        $id = $request->element_id;
        $isActive = $request->is_active;

        $element = null;
        if ($type === 'image') {
            $element = ImageElement::where('user_id', $userId)->find($id);
        } elseif ($type === 'divider') {
            $element = DividerElement::where('user_id', $userId)->find($id);
        } elseif ($type === 'text') {
            $element = TextElement::where('user_id', $userId)->find($id);
        } elseif ($type === 'video') {
            $element = VideoElement::where('user_id', $userId)->find($id);
        } elseif ($type === 'social') {
            $element = \App\Models\SocialMediaElement::where('user_id', $userId)->find($id);
        }

        if (!$element) {
            return response()->json(['success' => false, 'message' => 'Element not found or unauthorized'], 404);
        }

        $element->is_active = $isActive;
        $element->save();

        return response()->json(['success' => true]);
    }
}
