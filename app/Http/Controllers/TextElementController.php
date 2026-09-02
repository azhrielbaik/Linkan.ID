<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TextElementController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'content' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (str_word_count(strip_tags(html_entity_decode($value))) > 250) {
                    $fail('Isi teks konten tidak boleh lebih dari 250 kata.');
                }
            }],
            'element_id' => 'nullable|integer',
            'has_button' => 'nullable|boolean',
            'button_text' => 'nullable|string|max:50',
            'button_link' => 'nullable|string|max:255',
            'button_color' => 'nullable|string|max:20',
            'button_icon_type' => 'nullable|string|max:50',
            'button_icon_url' => 'nullable|string|max:255',
            'button_icon_emoji' => 'nullable|string|max:30',
            'button_icon_upload' => 'nullable|image|max:5120',
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
        $textElement->has_button = $request->has_button ?? false;
        $textElement->button_text = $request->button_text;
        $textElement->button_link = $request->button_link;
        $textElement->button_color = $request->button_color;
        
        if ($request->has('button_icon_type')) {
            $textElement->button_icon_type = $request->button_icon_type;
            
            if ($request->button_icon_type === 'emoji' || $request->button_icon_type === 'fontawesome') {
                $textElement->button_icon_value = $request->button_icon_emoji;
            } elseif ($request->button_icon_type === 'url') {
                $textElement->button_icon_value = $request->button_icon_url;
            } elseif ($request->button_icon_type === 'upload' && $request->hasFile('button_icon_upload')) {
                // Delete old if exists
                if ($textElement->button_icon_type === 'upload' && $textElement->button_icon_value) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($textElement->button_icon_value);
                }

                $file = $request->file('button_icon_upload');
                $filename = 'text_icons/' . time() . '_' . \Illuminate\Support\Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.webp';
                
                $image = \Intervention\Image\Laravel\Facades\Image::decode($file)->scaleDown(width: 200);
                $encoded = $image->encodeUsingFileExtension('webp', quality: 80);
                \Illuminate\Support\Facades\Storage::disk('public')->put($filename, (string) $encoded);
                
                $textElement->button_icon_value = $filename;
            } elseif ($request->button_icon_type === 'none') {
                $textElement->button_icon_value = null;
            }
        }

        $textElement->save();

        return response()->json([
            'success' => true,
            'id' => $textElement->id,
            'content' => $textElement->content,
            'has_button' => $textElement->has_button,
            'button_text' => $textElement->button_text,
            'button_link' => $textElement->button_link,
            'button_color' => $textElement->button_color,
            'button_icon_type' => $textElement->button_icon_type,
            'button_icon_value' => $textElement->button_icon_value,
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
