<?php

namespace App\Logging;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

class SanitizeSensitiveDataProcessor implements ProcessorInterface
{
    /**
     * List of sensitive key names that should always be sanitized.
     *
     * @var array<int, string>
     */
    protected array $sensitiveKeys = [
        'password',
        'password_confirmation',
        'current_password',
        'new_password',
        'old_password',
        'token',
        'access_token',
        'refresh_token',
        'reset_token',
        'csrf_token',
        'api_token',
        'remember_token',
        'secret',
        'client_secret',
        'server_key',
        'client_key',
        'signature_key',
        'card_number',
        'credit_card',
        'cc_number',
        'pan',
        'cvv',
        'cvc',
        'cvv2',
        'cid',
        'authorization',
        'auth_header',
        'bearer',
        'cookie',
        'cookies',
        'set-cookie',
        'laravel_session',
        'session_id',
        'otp',
        'otp_code',
        'pin',
        'account_number',
    ];

    /**
     * Monolog 3 processor invoke method.
     */
    public function __invoke(LogRecord $record): LogRecord
    {
        try {
            $sanitizedContext = $this->sanitize($record->context);
            $sanitizedExtra = $this->sanitize($record->extra);

            return $record->with(
                context: is_array($sanitizedContext) ? $sanitizedContext : [],
                extra: is_array($sanitizedExtra) ? $sanitizedExtra : []
            );
        } catch (\Throwable) {
            // Fail-safe: Monolog processor must never crash application
            return $record;
        }
    }

    /**
     * Recursively sanitize sensitive keys in array or object.
     */
    public function sanitize(mixed $data): mixed
    {
        if (is_array($data)) {
            $result = [];
            foreach ($data as $key => $value) {
                if ($this->isSensitiveKey((string) $key)) {
                    $result[$key] = '[REDACTED]';
                } else {
                    $result[$key] = $this->sanitize($value);
                }
            }

            return $result;
        }

        if (is_object($data)) {
            if ($data instanceof \Throwable) {
                return [
                    'class' => get_class($data),
                    'message' => $data->getMessage(),
                    'file' => $data->getFile(),
                    'line' => $data->getLine(),
                ];
            }

            // If object has toArray or is stdClass
            if (method_exists($data, 'toArray')) {
                return $this->sanitize($data->toArray());
            }

            if ($data instanceof \JsonSerializable) {
                return $this->sanitize($data->jsonSerialize());
            }

            return $this->sanitize((array) $data);
        }

        if (is_string($data)) {
            return $this->sanitizeStringPatterns($data);
        }

        return $data;
    }

    /**
     * Alias helper for array sanitization.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function sanitizeArray(array $data): array
    {
        $sanitized = $this->sanitize($data);

        return is_array($sanitized) ? $sanitized : [];
    }

    /**
     * Check if key name matches sensitive keywords.
     */
    protected function isSensitiveKey(string $key): bool
    {
        $cleanKey = strtolower(trim($key));

        if (in_array($cleanKey, $this->sensitiveKeys, true)) {
            return true;
        }

        // Fuzzy match for composite keys (e.g., user_password, midtrans_server_key, auth_token)
        return (bool) preg_match('/(password|token|secret|server_key|client_key|signature_key|card_number|credit_card|cvv|cvc|authorization|cookie|session_id)/i', $cleanKey);
    }

    /**
     * Sanitize sensitive string patterns like Bearer tokens or credit card numbers.
     */
    protected function sanitizeStringPatterns(string $value): string
    {
        // Redact Bearer tokens in string values
        if (stripos($value, 'Bearer ') !== false) {
            $value = preg_replace('/Bearer\s+[A-Za-z0-9\-._~+\/]+=*/i', 'Bearer [REDACTED]', $value) ?? $value;
        }

        // Redact 13-16 digit payment card numbers (preserving last 4 digits)
        if (preg_match('/\b\d{4}[ -]?\d{4}[ -]?\d{4}[ -]?\d{4}\b/', $value)) {
            $value = preg_replace_callback('/\b(\d{4})[ -]?\d{4}[ -]?\d{4}[ -]?(\d{4})\b/', function ($matches) {
                return '****-****-****-'.$matches[2];
            }, $value) ?? $value;
        }

        return $value;
    }
}

