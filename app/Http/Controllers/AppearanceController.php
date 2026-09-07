<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appearance;
use App\Models\DigitalProduct;
use App\Http\Requests\UpdateAppearanceRequest;
use App\Http\Requests\UpdateDesignSettingsRequest;
use App\Services\AppearanceService;

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
        // 1. Ekstrak data murni (tanpa membocorkan instance Request ke Service)
        $payload = $request->validated();
        $bannerFile = $request->file('banner');
        $profileFile = $request->file('profile_image');

        // 2. Delegasikan ke Service
        $appearance = $this->appearanceService->updateAppearance(
            Auth::user(), 
            $payload, 
            $bannerFile, 
            $profileFile
        );

        // 3. Kembalikan HTTP Response
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui!',
                'appearance' => $appearance
            ]);
        }

        return redirect()->back()->with('success', 'Appearance updated successfully!');
    }

    /**
     * Auto-save design settings via AJAX.
     * Logika ini relatif ringan, namun bisa dipindah ke service yang sama jika dibutuhkan nanti.
     */
    public function updateDesignSettings(UpdateDesignSettingsRequest $request)
    {
        // Otorisasi kepemilikan sudah dilakukan di FormRequest (authorize method)
        $appearance = Appearance::findOrFail($request->appearance_id);

        $appearance->fill($request->safe()->only([
            'background_type',
            'background_color',
            'profile_layout',
            'block_shape'
        ]))->save();

        return response()->json([
            'success'    => true,
            'appearance' => $appearance->only(
                'background_type', 'background_color', 'profile_layout', 'block_shape'
            ),
        ]);
    }
}
