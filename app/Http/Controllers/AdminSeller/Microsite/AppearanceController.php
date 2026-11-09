<?php

namespace App\Http\Controllers\AdminSeller\Microsite;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAppearanceRequest;
use App\Http\Requests\UpdateDesignSettingsRequest;
use App\Models\Appearance;
use App\Models\DigitalProduct;
use App\Services\AppearanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppearanceController extends Controller
{
    private AppearanceService $appearanceService;

    public function __construct(AppearanceService $appearanceService)
    {
        $this->appearanceService = $appearanceService;
    }

    public function index()
    {
        $user = Auth::user();
        $appearance = Appearance::where('user_id', $user->id)->first();
        $digitalProducts = DigitalProduct::where('user_id', $user->id)->latest()->get();

        return view('homeadminS.appearance', compact('appearance', 'digitalProducts'));
    }

    public function update(UpdateAppearanceRequest $request)
    {
        $payload = $request->validated();
        $bannerFile = $request->file('banner');
        $profileFile = $request->file('profile_image');

        $appearance = $this->appearanceService->updateAppearance(
            Auth::user(),
            $payload,
            $bannerFile,
            $profileFile
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui!',
                'appearance' => $appearance,
            ]);
        }

        return redirect()->back()->with('success', 'Appearance updated successfully!');
    }

    public function updateDesignSettings(UpdateDesignSettingsRequest $request)
    {
        $appearance = Appearance::findOrFail($request->appearance_id);

        $appearance->fill($request->safe()->only([
            'background_type',
            'background_color',
            'profile_layout',
            'block_shape',
        ]))->save();

        return response()->json([
            'success' => true,
            'appearance' => $appearance->only(
                'background_type', 'background_color', 'profile_layout', 'block_shape'
            ),
        ]);
    }

    public function updateOrder(Request $request): JsonResponse
    {
        $request->validate([
            'blocks_order' => 'required|string',
            'appearance_id' => 'required|integer|exists:appearances,id',
        ]);

        $user = Auth::user();
        $appearance = Appearance::where('user_id', $user->id)->findOrFail($request->appearance_id);

        $appearance->blocks_order = $request->blocks_order;
        $appearance->save();

        return response()->json(['success' => true]);
    }
}
