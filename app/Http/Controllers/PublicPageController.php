<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class PublicPageController extends Controller
{
    public function show($username)
    {
        // Parameter $username is now an alias for the Appearance
        $appearance = \App\Models\Appearance::where('alias', $username)->firstOrFail();
        $user = $appearance->user;

        if ($user->isSuspended()) {
            abort(403, 'Profil atau tautan ini sedang ditangguhkan.');
        }

        // Dapatkan IP dan User Agent
        $ipAddress = request()->ip();
        $userAgent = request()->header('User-Agent');

        // Cek apakah hari ini sudah pernah view dari kombinasi IP dan User Agent yang sama
        $existing = DB::table('link_views')
            ->where('link_id', $appearance->alias)
            ->where('ip_address', $ipAddress)
            ->where('user_agent', $userAgent)
            ->whereDate('created_at', now()->toDateString())
            ->first();

        if (!$existing) {
            DB::table('link_views')->insert([
                'user_id' => $user->id,
                'link_id' => $appearance->alias,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Ambil data produk digital user yang aktif (sekarang per appearance jika dibutuhkan, tapi sementara masih per user/appearance jika sudah diupdate)
        $products = \App\Models\DigitalProduct::where('user_id', $user->id)
            ->where('is_active', 1)
            ->get();

        // Ambil data shortlink user (masih per user untuk saat ini, atau mungkin tidak ditampilkan)
        $shortlinks = \App\Models\Shortlink::where('user_id', $user->id)
            ->latest()
            ->get();

        // Ambil data image, divider, dan text elements yang aktif per appearance
        $imageElements = \App\Models\ImageElement::where('appearance_id', $appearance->id)->where('is_active', true)->get();
        $dividerElements = \App\Models\DividerElement::where('appearance_id', $appearance->id)->where('is_active', true)->get();
        $textElements = \App\Models\TextElement::where('appearance_id', $appearance->id)->where('is_active', true)->get();
        $videoElements = \App\Models\VideoElement::where('appearance_id', $appearance->id)->where('is_active', true)->get();
        $socialMediaElements = \App\Models\SocialMediaElement::where('appearance_id', $appearance->id)->where('is_active', true)->get();

        return view('public.profile', compact('user', 'appearance', 'products', 'shortlinks', 'imageElements', 'dividerElements', 'textElements', 'videoElements', 'socialMediaElements'));
    }
}
