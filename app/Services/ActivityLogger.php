<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    /**
     * Catat aktivitas admin ke database.
     *
     * @param string $action Nama aksi (misal: 'approve_product', 'suspend_user')
     * @param string $description Penjelasan aktivitas
     * @param array $properties Metadata tambahan
     * @param int|null $userId User ID yang melakukan (default Auth::id())
     * @return ActivityLog|null
     */
    public static function log(string $action, string $description, array $properties = [], ?int $userId = null): ?ActivityLog
    {
        try {
            return ActivityLog::create([
                'user_id' => $userId ?? Auth::id(),
                'action' => $action,
                'description' => $description,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'properties' => !empty($properties) ? $properties : null,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to log admin activity: ' . $e->getMessage());
            return null;
        }
    }
}
