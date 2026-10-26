<?php

namespace App\Http\Controllers\PlatformAdmin;

use App\Http\Controllers\Controller;
use App\Services\PlatformAdminService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected PlatformAdminService $platformAdminService;

    public function __construct(PlatformAdminService $platformAdminService)
    {
        $this->platformAdminService = $platformAdminService;
    }

    public function beranda()
    {
        $stats = $this->platformAdminService->getDashboardStats();

        return view('platformadmin.berandaplatform', $stats);
    }

    public function getCommissions(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'total_earnings' => $this->platformAdminService->getTotalEarnings(),
            'commissions' => $this->platformAdminService->getCommissionsData(),
        ]);
    }

    public function getNotifications(Request $request)
    {
        $readKeys = session('platform_notif_read_keys', []);

        return response()->json($this->platformAdminService->getNotificationsData($readKeys));
    }

    public function markNotificationRead(Request $request)
    {
        $request->validate([
            'notification_key' => 'required|string|max:100',
        ]);

        $key = $request->input('notification_key');
        $readKeys = session('platform_notif_read_keys', []);

        if (! in_array($key, $readKeys, true)) {
            $readKeys[] = $key;
            if (count($readKeys) > 200) {
                $readKeys = array_slice($readKeys, -200);
            }
            session(['platform_notif_read_keys' => $readKeys]);
        }

        return response()->json(['status' => 'success', 'key' => $key]);
    }

    public function markAllNotificationsRead(Request $request)
    {
        $currentData = $this->platformAdminService->getNotificationsData([]);
        $allKeys = array_map(fn ($n) => $n['id'], $currentData['notifications'] ?? []);

        $readKeys = session('platform_notif_read_keys', []);
        $merged = array_unique(array_merge($readKeys, $allKeys));

        if (count($merged) > 200) {
            $merged = array_slice($merged, -200);
        }

        session(['platform_notif_read_keys' => $merged]);

        return response()->json(['status' => 'success', 'marked_count' => count($allKeys)]);
    }

    public function streamNotifications(Request $request)
    {
        $readKeys = session('platform_notif_read_keys', []);

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }

        $data = $this->platformAdminService->getNotificationsData($readKeys);

        return response()->stream(function () use ($data) {
            echo "event: notifications\n";
            echo 'data: '.json_encode($data)."\n\n";
            if (ob_get_level()) {
                @ob_flush();
            }
            flush();
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Connection' => 'close',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}
