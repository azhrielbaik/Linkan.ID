<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
        'user_agent',
        'properties',
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
    ];

    public static function maskEmail(?string $email): string
    {
        if (!$email || !str_contains($email, '@')) {
            return '-';
        }

        [$local, $domain] = explode('@', $email, 2);
        $visible = substr($local, 0, 1);

        return $visible . str_repeat('*', max(2, strlen($local) - 1)) . '@' . $domain;
    }

    public static function maskIp(?string $ip): string
    {
        if (!$ip) {
            return '-';
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $parts = explode('.', $ip);
            $parts[3] = '*';
            return implode('.', $parts);
        }

        return substr($ip, 0, 4) . ':****';
    }

    public static function maskSensitiveText(?string $text): string
    {
        if (!$text) {
            return '-';
        }

        return preg_replace(
            '/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}\b/i',
            '[email disembunyikan]',
            $text
        ) ?? $text;
    }

    /**
     * Relasi ke User (Admin yang melakukan tindakan).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
