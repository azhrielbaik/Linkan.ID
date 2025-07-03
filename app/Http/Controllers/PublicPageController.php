<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class PublicPageController extends Controller
{
    public function show($username)
    {
        $user = User::where('username', $username)->firstOrFail();

        // Dapatkan IP dan User Agent
        $ipAddress = request()->ip();
        $userAgent = request()->header('User-Agent');

        // Cek apakah hari ini sudah pernah view dari kombinasi IP dan User Agent yang sama
        $existing = DB::table('link_views')
            ->where('link_id', $user->username)
            ->where('ip_address', $ipAddress)
            ->where('user_agent', $userAgent)
            ->whereDate('created_at', now()->toDateString())
            ->first();

        if (!$existing) {
            DB::table('link_views')->insert([
                'link_id' => $user->username,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Ambil data tampilan (appearance)
        $appearance = \App\Models\Appearance::where('user_id', $user->id)->first();

        // Ambil data produk digital user yang aktif
        $products = \App\Models\DigitalProduct::where('user_id', $user->id)
            ->where('is_active', 1)
            ->get();

        return view('public.profile', compact('user', 'appearance', 'products'));
    }
}
