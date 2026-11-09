<?php

namespace App\Http\Controllers\AdminSeller\Microsite;

use App\Http\Controllers\Controller;
use App\Models\VideoElement;
use App\Services\AdminSeller\MicrositeElementManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VideoElementController extends Controller
{
    public function __construct(
        protected MicrositeElementManager $elementManager
    ) {}

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'video_url' => 'nullable|string',
            'is_autoplay' => 'nullable',
            'element_id' => 'nullable|integer',
            'appearance_id' => 'required|integer|exists:appearances,id',
        ]);

        $element = $this->elementManager->storeVideo(
            (int) auth()->id(),
            (int) $request->appearance_id,
            $request->video_url,
            filter_var($request->is_autoplay, FILTER_VALIDATE_BOOLEAN),
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

    public function destroy(Request $request, $id): JsonResponse
    {
        $appearanceId = $request->input('appearance_id') ? (int) $request->input('appearance_id') : null;
        $success = $this->elementManager->destroy('video', (int) $id, (int) auth()->id(), $appearanceId);

        if ($success) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }
}
