<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Appearance;

class AppearanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $appearance = Appearance::where('user_id', $user->id)->first();
        $digitalProducts = \App\Models\DigitalProduct::where('user_id', $user->id)->latest()->get();
        return view('homeadminS.appearance', compact('appearance', 'digitalProducts'));
    }


    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'appearance_id' => 'required|integer|exists:appearances,id',
            'name' => ['required', 'string', function ($attribute, $value, $fail) {
                if (str_word_count(strip_tags(html_entity_decode($value))) > 50) {
                    $fail('Nama profil tidak boleh lebih dari 50 kata.');
                }
            }],
            'bio' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (str_word_count(strip_tags(html_entity_decode($value))) > 250) {
                    $fail('Deskripsi / Bio profil tidak boleh lebih dari 250 kata.');
                }
            }],
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'profile_shape' => 'nullable|string|in:circle,rounded,square',
            'theme_color' => 'nullable|string|max:7',
            'background_color' => 'nullable|string',
            'background_type' => 'nullable|string|in:color,image',
            'profile_layout' => 'nullable|string|in:title-top,classic,side',
            'block_shape' => 'nullable|string|in:sharp,rounded,pill',
            'instagram' => 'nullable|url|max:255',
            'tiktok' => 'nullable|url|max:255',
            'whatsapp' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'facebook' => 'nullable|url|max:255',
            'website' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
            'telegram' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
            'discord' => 'nullable|url|max:255',
        ]);

        // Cari record appearance spesifik
        $appearance = Appearance::where('user_id', $user->id)->findOrFail($request->appearance_id);

        // Cek jika ada request untuk menghapus banner
        if ($request->input('delete_banner') == 1) {
            if ($appearance && $appearance->banner) {
                Storage::delete('public/' . $appearance->banner);
                $appearance->banner = null;
            }
        }

        if ($request->has('delete_profile_image') && $request->delete_profile_image == 1) {
            if ($appearance->profile_image && Storage::exists('public/' . $appearance->profile_image)) {
                Storage::delete('public/' . $appearance->profile_image);
            }
            $appearance->profile_image = null;
        }

        // Update data
        $appearance->name = $request->name;
        $appearance->bio = $request->bio;
        if ($request->filled('profile_shape')) {
            $appearance->profile_shape = $request->profile_shape;
        }
        if ($request->filled('theme_color')) {
            $appearance->theme_color = $request->theme_color;
        } elseif (!$appearance->theme_color) {
            $appearance->theme_color = '#FF9040';
        }
        if ($request->filled('background_color')) {
            $appearance->background_color = $request->background_color;
        } elseif (!$appearance->background_color) {
            $appearance->background_color = '#FFFFFF';
        }
        if ($request->filled('background_type')) {
            $appearance->background_type = $request->background_type;
        }
        if ($request->filled('profile_layout')) {
            $appearance->profile_layout = $request->profile_layout;
        }
        if ($request->filled('block_shape')) {
            $appearance->block_shape = $request->block_shape;
        }
        $appearance->instagram = $request->instagram;
        $appearance->tiktok = $request->tiktok;
        $appearance->whatsapp = $request->whatsapp;
        $appearance->linkedin = $request->linkedin;
$appearance->facebook = $request->facebook;
$appearance->website = $request->website;
$appearance->twitter = $request->twitter;
$appearance->youtube = $request->youtube;
$appearance->telegram = $request->telegram;
$appearance->email = $request->email;
$appearance->discord = $request->discord;

        $appearance->is_active = true;

        // Handle banner upload
        if ($request->hasFile('banner')) {
            if ($appearance->banner) {
                Storage::delete('public/' . $appearance->banner);
            }
            $file = $request->file('banner');
            $bannerPath = 'appearances/banners/' . time() . '_' . \Illuminate\Support\Str::random(10) . '.webp';

            $image = \Intervention\Image\ImageManager::gd()->read($file)
                ->scaleDown(width: 1200);
            $encoded = $image->toWebp(80);

            Storage::disk('public')->put($bannerPath, (string) $encoded);
            $appearance->banner = $bannerPath;
        }

        // Handle profile image upload
        if ($request->has('profile_image_base64') && !empty($request->profile_image_base64)) {
            if ($appearance->profile_image) {
                Storage::delete('public/' . $appearance->profile_image);
            }
            
            // Mengambil base64 string
            $image_parts = explode(";base64,", $request->profile_image_base64);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1] ?? 'webp';
            $image_base64 = base64_decode($image_parts[1]);

            $profilePath = 'appearances/profiles/' . time() . '_' . \Illuminate\Support\Str::random(10) . '.webp';
            
            // Proses dengan Intervention Image
            $image = \Intervention\Image\Laravel\Facades\Image::decode($image_base64)
                ->scaleDown(width: 500);
            $encoded = $image->encodeUsingFileExtension('webp', quality: 80);
                
            Storage::disk('public')->put($profilePath, (string) $encoded);
            $appearance->profile_image = $profilePath;
        } else if ($request->hasFile('profile_image')) {
            if ($appearance->profile_image) {
                Storage::delete('public/' . $appearance->profile_image);
            }
            $file = $request->file('profile_image');
            $profilePath = 'appearances/profiles/' . time() . '_' . \Illuminate\Support\Str::random(10) . '.webp';

            $image = \Intervention\Image\ImageManager::gd()->read($file)
                ->scaleDown(width: 500);
            $encoded = $image->toWebp(80);

            Storage::disk('public')->put($profilePath, (string) $encoded);
            $appearance->profile_image = $profilePath;
        }

        $appearance->save();

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
     * Auto-save design settings (background, layout, block shape) via AJAX.
     * Dipanggil dari panel "Pengaturan" di editor microsite tanpa reload halaman.
     */
    public function updateDesignSettings(Request $request)
    {
        $request->validate([
            'appearance_id' => 'required|integer|exists:appearances,id',
            'background_type' => 'nullable|string|in:color,image',
            'background_color' => 'nullable|string|max:100',
            'profile_layout'  => 'nullable|string|in:classic,title-top,side',
            'block_shape'     => 'nullable|string|in:sharp,rounded,pill',
        ]);

        $appearance = Appearance::where('user_id', Auth::id())->findOrFail($request->appearance_id);

        $appearance->fill($request->only(
            'background_type',
            'background_color',
            'profile_layout',
            'block_shape'
        ))->save();

        return response()->json([
            'success'    => true,
            'appearance' => $appearance->only(
                'background_type', 'background_color', 'profile_layout', 'block_shape'
            ),
        ]);
    }
}
