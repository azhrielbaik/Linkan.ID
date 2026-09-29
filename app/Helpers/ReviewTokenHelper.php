<?php

namespace App\Helpers;

class ReviewTokenHelper
{
    /**
     * Generate HMAC token untuk akses halaman review.
     * Format token: base64url(order_id) . '.' . hmac_sha256
     */
    public static function generate(string $orderId): string
    {
        $payload = rtrim(strtr(base64_encode($orderId), '+/', '-_'), '=');
        $signature = hash_hmac('sha256', $orderId, config('app.key'));
        return $payload . '.' . $signature;
    }

    /**
     * Verifikasi token dan kembalikan order_id jika valid, null jika tidak.
     */
    public static function verify(string $token): ?string
    {
        $parts = explode('.', $token, 2);
        if (count($parts) !== 2) {
            return null;
        }

        [$payload, $signature] = $parts;
        // Decode base64url or standard base64
        $remainder = strlen($payload) % 4;
        if ($remainder) {
            $payload .= str_repeat('=', 4 - $remainder);
        }
        $orderId = base64_decode(strtr($payload, '-_', '+/'));
        if (!$orderId) {
            return null;
        }

        $expected = hash_hmac('sha256', $orderId, config('app.key'));
        if (!hash_equals($expected, $signature)) {
            return null;
        }

        return $orderId;
    }
}
