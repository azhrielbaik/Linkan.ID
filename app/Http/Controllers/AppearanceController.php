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
    'name' => 'required|string|max:255',
    'bio' => 'nullable|string|max:500',
    'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    'theme_color' => 'required|string|max:7',
    'background_color' => 'nullable|string',
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


        // Cari atau buat record appearance
        $appearance = Appearance::where('user_id', $user->id)->first();
        if (!$appearance) {
            $appearance = new Appearance();
            $appearance->user_id = $user->id;
        }
          // Cek jika ada request untuk menghapus banner
if ($request->input('delete_banner') == 1) {
    if ($appearance && $appearance->banner) {
        Storage::delete($appearance->banner);
        $appearance->banner = null;
    }
}


if ($request->has('delete_profile_image') && $request->delete_profile_image == 1) {
    // Hapus file lama
    if ($appearance->profile_image && Storage::exists('public/' . $appearance->profile_image)) {
        Storage::delete('public/' . $appearance->profile_image);
    }

    // Setel jadi null (atau default jika pakai path khusus)
    $appearance->profile_image = null;
}



        // Update data
        $appearance->name = $request->name;
        $appearance->bio = $request->bio;
        $appearance->theme_color = $request->theme_color;
        $appearance->background_color = $request->background_color;
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
            $bannerPath = $request->file('banner')->store('appearances/banners', 'public');
            $appearance->banner = $bannerPath;
        }

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            if ($appearance->profile_image) {
                Storage::delete('public/' . $appearance->profile_image);
            }
            $profilePath = $request->file('profile_image')->store('appearances/profiles', 'public');
            $appearance->profile_image = $profilePath;
        }

        $appearance->save();

        return redirect()->back()->with('success', 'Appearance updated successfully!');
    }
}
