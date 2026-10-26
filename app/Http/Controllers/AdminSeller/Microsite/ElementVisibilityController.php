<?php

namespace App\Http\Controllers\AdminSeller\Microsite;

use App\Http\Controllers\Controller;
use App\Services\AdminSeller\MicrositeElementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ElementVisibilityController extends Controller
{
    public function __construct(
        protected MicrositeElementService $elementService
    ) {}

    public function toggle(Request $request)
    {
        $request->validate([
            'element_type' => 'required|string|in:image,divider,text,video,social',
            'element_id' => 'required|integer',
            'is_active' => 'required|boolean',
        ]);

        $success = $this->elementService->toggleVisibility(
            $request->element_type,
            (int) $request->element_id,
            (int) Auth::id(),
            (bool) $request->is_active
        );

        if (! $success) {
            return response()->json(['success' => false, 'message' => 'Element not found or unauthorized'], 404);
        }

        return response()->json(['success' => true]);
    }
}
