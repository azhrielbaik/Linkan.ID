<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AppearanceService;
use Illuminate\Support\Facades\DB;

class PublicPageController extends Controller
{
    public function __construct(
        private AppearanceService $appearanceService
    ) {}

    public function show($username)
    {
        // Parameter $username can be an appearance alias or user username (eager load user to eliminate N+1)
        $appearance = \App\Models\Appearance::with('user')->where('alias', $username)->first();
        if (! $appearance) {
            $matchedUser = User::where('username', $username)->first();
            if ($matchedUser) {
                $appearance = \App\Models\Appearance::with('user')
                    ->where('user_id', $matchedUser->id)
                    ->latest()
                    ->first();
            }
        }

        if (! $appearance) {
            abort(404, 'Profil atau tautan tidak ditemukan.');
        }

        $user = $appearance->user;

        if ($user->isSuspended()) {
            abort(403, 'Profil atau tautan ini sedang ditangguhkan.');
        }

        // Dapatkan IP dan User Agent
        $ipAddress = request()->ip();
        $userAgent = request()->header('User-Agent');

        // Cek apakah hari ini sudah pernah view dari kombinasi IP dan User Agent yang sama (menggunakan index range scan)
        $todayStart = now()->startOfDay();
        $todayEnd = now()->endOfDay();
        $alreadyViewedToday = DB::table('link_views')
            ->where('link_id', $appearance->alias)
            ->where('ip_address', $ipAddress)
            ->where('user_agent', $userAgent)
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->exists();

        if (! $alreadyViewedToday) {
            DB::table('link_views')->insert([
                'user_id' => $user->id,
                'link_id' => $appearance->alias,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Susun urutan blok elemen microsite secara bersih melalui AppearanceService
        $sortedBlocks = $this->appearanceService->getSortedBlocksForPublic($appearance, $user);

        return view('public.profile', compact('user', 'appearance', 'sortedBlocks'));
    }
}
