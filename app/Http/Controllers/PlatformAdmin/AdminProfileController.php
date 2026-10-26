<?php

namespace App\Http\Controllers\PlatformAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class AdminProfileController extends Controller
{
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'new_password.required' => 'Kata sandi baru wajib diisi.',
            'new_password.min' => 'Kata sandi baru minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi kata sandi baru tidak sesuai.',
        ]);

        /** @var User $user */
        $user = Auth::user();

        if (! Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Kata sandi saat ini tidak cocok.',
            ], 422);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        ActivityLogger::log(
            'admin_change_password',
            "Admin ({$user->name}) berhasil memperbarui kata sandi akun.",
            ['user_id' => $user->id]
        );

        return response()->json([
            'success' => true,
            'message' => 'Kata sandi akun berhasil diperbarui.',
        ]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.max' => 'Nama lengkap maksimal 100 karakter.',
            'avatar.image' => 'File avatar harus berupa gambar.',
            'avatar.mimes' => 'Format avatar yang didukung: JPEG, PNG, JPG, WEBP.',
            'avatar.max' => 'Ukuran avatar maksimal 2MB.',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->name = strip_tags(trim($request->name));

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        ActivityLogger::log(
            'admin_update_profile',
            "Admin ({$user->name}) berhasil memperbarui profil & avatar akun.",
            ['user_id' => $user->id, 'name' => $user->name, 'has_avatar' => ! empty($user->avatar)]
        );

        $avatarUrl = $user->avatar ? asset('storage/'.$user->avatar) : null;

        return response()->json([
            'success' => true,
            'message' => 'Profil admin berhasil diperbarui.',
            'name' => $user->name,
            'avatar_url' => $avatarUrl,
            'initials' => strtoupper(substr($user->name, 0, 2)),
        ]);
    }

    public function deleteAvatar(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->avatar = null;
        $user->save();

        ActivityLogger::log(
            'admin_delete_avatar',
            "Admin ({$user->name}) menghapus foto avatar akun.",
            ['user_id' => $user->id]
        );

        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil dihapus.',
            'initials' => strtoupper(substr($user->name, 0, 2)),
        ]);
    }

    public function getActiveSessions(Request $request)
    {
        $userId = Auth::id();
        $currentSessionId = session()->getId();
        $sessions = [];

        try {
            if (Schema::hasTable('sessions')) {
                $rawSessions = DB::table('sessions')
                    ->where('user_id', $userId)
                    ->orderBy('last_activity', 'desc')
                    ->get();

                foreach ($rawSessions as $session) {
                    $userAgent = $session->user_agent ?? '';
                    $parsed = $this->parseUserAgent($userAgent);

                    $sessions[] = [
                        'id' => $session->id,
                        'is_current' => ($session->id === $currentSessionId),
                        'ip_address' => $session->ip_address ?? '127.0.0.1',
                        'browser' => $parsed['browser'],
                        'platform' => $parsed['platform'],
                        'device_type' => $parsed['device_type'],
                        'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                        'exact_time' => Carbon::createFromTimestamp($session->last_activity)->translatedFormat('d M Y, H:i'),
                    ];
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Gagal membaca tabel sessions: '.$e->getMessage());
        }

        if (empty($sessions)) {
            $parsed = $this->parseUserAgent($request->userAgent() ?? '');
            $sessions[] = [
                'id' => $currentSessionId,
                'is_current' => true,
                'ip_address' => $request->ip() ?? '127.0.0.1',
                'browser' => $parsed['browser'],
                'platform' => $parsed['platform'],
                'device_type' => $parsed['device_type'],
                'last_active' => 'Sesi Saat Ini (Aktif Sekarang)',
                'exact_time' => now()->translatedFormat('d M Y, H:i'),
            ];
        }

        return response()->json([
            'success' => true,
            'sessions' => $sessions,
            'count' => count($sessions),
        ]);
    }

    public function revokeOtherSessions(Request $request)
    {
        $userId = Auth::id();
        $currentSessionId = session()->getId();

        try {
            if (Schema::hasTable('sessions')) {
                $deleted = DB::table('sessions')
                    ->where('user_id', $userId)
                    ->where('id', '!=', $currentSessionId)
                    ->delete();

                ActivityLogger::log(
                    'admin_revoke_sessions',
                    'Admin ('.Auth::user()->name.") mencabut {$deleted} sesi login dari perangkat lain.",
                    ['user_id' => $userId, 'sessions_revoked' => $deleted]
                );

                return response()->json([
                    'success' => true,
                    'message' => "Berhasil keluar dari {$deleted} sesi perangkat lain.",
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Gagal mencabut sesi lain: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kendala saat mencabut sesi lain.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Sesi perangkat lain berhasil dihentikan.',
        ]);
    }

    private function parseUserAgent(?string $userAgent): array
    {
        if (empty($userAgent)) {
            return ['browser' => 'Web Browser', 'platform' => 'Perangkat Dekstop', 'device_type' => 'desktop'];
        }

        $platform = 'Desktop';
        $deviceType = 'desktop';
        if (preg_match('/windows|win32/i', $userAgent)) {
            $platform = 'Windows PC';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            $platform = 'macOS';
        } elseif (preg_match('/android/i', $userAgent)) {
            $platform = 'Android';
            $deviceType = 'mobile';
        } elseif (preg_match('/iphone|ipad|ipod/i', $userAgent)) {
            $platform = 'iOS (Apple)';
            $deviceType = 'mobile';
        } elseif (preg_match('/linux/i', $userAgent)) {
            $platform = 'Linux';
        }

        $browser = 'Web Browser';
        if (preg_match('/edg/i', $userAgent)) {
            $browser = 'Microsoft Edge';
        } elseif (preg_match('/chrome|crios/i', $userAgent)) {
            $browser = 'Google Chrome';
        } elseif (preg_match('/firefox|fxios/i', $userAgent)) {
            $browser = 'Mozilla Firefox';
        } elseif (preg_match('/safari/i', $userAgent) && ! preg_match('/chrome/i', $userAgent)) {
            $browser = 'Apple Safari';
        } elseif (preg_match('/opera|opr/i', $userAgent)) {
            $browser = 'Opera';
        }

        return [
            'browser' => $browser,
            'platform' => $platform,
            'device_type' => $deviceType,
        ];
    }
}
