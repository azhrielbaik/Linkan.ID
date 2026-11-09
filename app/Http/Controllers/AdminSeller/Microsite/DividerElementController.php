<?php

namespace App\Http\Controllers\AdminSeller\Microsite;

use App\Http\Controllers\Controller;
use App\Services\AdminSeller\MicrositeElementManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DividerElementController extends Controller
{
    public function __construct(
        protected MicrositeElementManager $elementManager
    ) {}

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|string|in:line,space',
            'size' => 'required|integer|min:1|max:200',
            'element_id' => 'nullable|integer',
            'appearance_id' => 'required|integer|exists:appearances,id',
        ]);

        $dividerElement = $this->elementManager->storeDivider(
            (int) auth()->id(),
            (int) $request->appearance_id,
            $request->type,
            (int) $request->size,
            $request->element_id ? (int) $request->element_id : null
        );

        return response()->json([
            'success' => true,
            'id' => $dividerElement->id,
            'type' => $dividerElement->type,
            'size' => $dividerElement->size,
        ]);
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        $appearanceId = $request->input('appearance_id') ? (int) $request->input('appearance_id') : null;
        $success = $this->elementManager->destroy('divider', (int) $id, (int) auth()->id(), $appearanceId);

        if ($success) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }
}
