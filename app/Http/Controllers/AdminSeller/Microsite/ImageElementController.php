<?php

namespace App\Http\Controllers\AdminSeller\Microsite;

use App\Http\Controllers\Controller;
use App\Services\AdminSeller\MicrositeElementManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ImageElementController extends Controller
{
    public function __construct(
        protected MicrositeElementManager $elementManager
    ) {}

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'nullable|image|max:2048',
            'link_url' => 'nullable|url',
            'element_id' => 'nullable|integer',
            'appearance_id' => 'required|integer|exists:appearances,id',
        ]);

        $imageElement = $this->elementManager->storeImage(
            (int) $request->user()->id,
            (int) $request->appearance_id,
            $request->file('image'),
            $request->link_url,
            $request->element_id ? (int) $request->element_id : null
        );

        return response()->json([
            'success' => true,
            'id' => $imageElement->id,
            'image_url' => $imageElement->image_path ? asset('storage/'.$imageElement->image_path) : null,
        ]);
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        $appearanceId = $request->input('appearance_id') ? (int) $request->input('appearance_id') : null;
        $success = $this->elementManager->destroy('image', (int) $id, (int) $request->user()->id, $appearanceId);

        if ($success) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }
}
