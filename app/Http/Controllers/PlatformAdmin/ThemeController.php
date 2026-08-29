<?php

namespace App\Http\Controllers\PlatformAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThemeController extends Controller
{
    /**
     * Update the platform admin theme mode and accent color.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'theme' => 'nullable|string|in:light,dark',
            'theme_color' => ['nullable', 'string', 'regex:/^#([a-fA-F0-9]{3}|[a-fA-F0-9]{6})$/'],
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        if (isset($validated['theme'])) {
            $user->theme = $validated['theme'];
        }

        if (isset($validated['theme_color'])) {
            $user->theme_color = $validated['theme_color'];
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Tema berhasil diperbarui',
            'theme' => $user->theme ?? 'light',
            'theme_color' => $user->theme_color ?? '#ed842c',
        ]);
    }
}
