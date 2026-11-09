<?php

namespace App\Http\Controllers\AdminSeller\Microsite;

use App\Http\Controllers\Controller;
use App\Services\AdminSeller\MicrositeElementManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TextElementController extends Controller
{
    public function __construct(
        protected MicrositeElementManager $elementManager
    ) {}

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'content' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (str_word_count(strip_tags(html_entity_decode($value))) > 250) {
                    $fail('Isi teks konten tidak boleh lebih dari 250 kata.');
                }
            }],
            'element_id' => 'nullable|integer',
            'appearance_id' => 'required|integer|exists:appearances,id',
            'has_button' => 'nullable|boolean',
            'button_text' => 'nullable|string|max:50',
            'button_link' => 'nullable|string|max:255',
            'button_color' => 'nullable|string|max:20',
            'button_icon_type' => 'nullable|string|max:50',
            'button_icon_url' => 'nullable|string|max:255',
            'button_icon_emoji' => 'nullable|string|max:30',
            'button_icon_upload' => 'nullable|image|max:5120',
        ]);

        $textElement = $this->elementManager->storeText(
            (int) $request->user()->id,
            (int) $request->appearance_id,
            $request->all(),
            $request->file('button_icon_upload'),
            $request->element_id ? (int) $request->element_id : null
        );

        return response()->json([
            'success' => true,
            'id' => $textElement->id,
            'content' => $textElement->content,
            'has_button' => (bool) $textElement->has_button,
            'button_text' => $textElement->button_text,
            'button_link' => $textElement->button_link,
            'button_color' => $textElement->button_color,
            'button_icon_type' => $textElement->button_icon_type,
            'button_icon_value' => $textElement->button_icon_value,
        ]);
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        $appearanceId = $request->input('appearance_id') ? (int) $request->input('appearance_id') : null;
        $success = $this->elementManager->destroy('text', (int) $id, (int) $request->user()->id, $appearanceId);

        if ($success) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }
}
