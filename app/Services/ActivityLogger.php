<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ActivityLogger
{
    private const SENSITIVE_KEYS = [
        'password', 'password_confirmation', 'otp', 'otp_code',
        'token', 'secret', 'server_key', 'client_key', 'api_key',
        'access_token', 'refresh_token', 'account_number',
    ];
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
                'description' => self::redactText($description),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'properties' => !empty($properties) ? self::sanitizeProperties($properties) : null,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log admin activity: ' . $e->getMessage());
            return null;
        }
    }

    private static function sanitizeProperties(array $properties): array
    {
        $sanitized = [];

        foreach ($properties as $key => $value) {
            $normalizedKey = strtolower((string) $key);
            if (in_array($normalizedKey, self::SENSITIVE_KEYS, true)) {
                $sanitized[$key] = '[REDACTED]';
                continue;
            }

            $sanitized[$key] = is_array($value)
                ? self::sanitizeProperties($value)
                : (is_string($value) ? self::redactText($value) : $value);
        }

        return $sanitized;
    }

    private static function redactText(string $text): string
    {
        return preg_replace(
            '/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}\b/i',
            '[EMAIL REDACTED]',
            $text
        ) ?? $text;
    }
}
