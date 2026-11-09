<?php

namespace App\Http\Controllers\AdminSeller\Microsite;

use App\Http\Controllers\Controller;
use App\Models\SocialMediaElement;
use App\Services\AdminSeller\MicrositeElementManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SocialMediaElementController extends Controller
{
    public function __construct(
        protected MicrositeElementManager $elementManager
    ) {}

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'platforms' => 'nullable|array',
            'element_id' => 'nullable|integer',
            'appearance_id' => 'required|integer|exists:appearances,id',
        ]);

        $element = $this->elementManager->storeSocial(
            (int) auth()->id(),
            (int) $request->appearance_id,
            $request->platforms ?? [],
            $request->element_id ? (int) $request->element_id : null
        );

        return response()->json([
            'success' => true,
            'id' => $element->id,
            'element' => $element,
        ]);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $request->validate([
            'platforms' => 'nullable|array',
        ]);

        $element = SocialMediaElement::where('user_id', auth()->id())->find($id);
        if ($element) {
            $platforms = [];
            if ($request->platforms && is_array($request->platforms)) {
                foreach ($request->platforms as $key => $val) {
                    if (! empty($val)) {
                        $platforms[$key] = $val;
                    }
                }
            }

            $element->platforms = $platforms;
            $element->save();

            return response()->json([
                'success' => true,
                'element' => $element,
            ]);
        }

        return response()->json(['success' => false], 404);
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        $appearanceId = $request->input('appearance_id') ? (int) $request->input('appearance_id') : null;
        $success = $this->elementManager->destroy('social', (int) $id, (int) auth()->id(), $appearanceId);

        if ($success) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }
}
