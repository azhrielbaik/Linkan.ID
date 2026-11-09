<?php

namespace App\Logging;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Monolog\Formatter\FormatterInterface;
use Monolog\LogRecord;

class StructuredJsonFormatter implements FormatterInterface
{
    protected SanitizeSensitiveDataProcessor $sanitizer;

    public function __construct()
    {
        $this->sanitizer = new SanitizeSensitiveDataProcessor();
    }

    /**
     * Formats a log record into a single structured JSON line.
     */
    public function format(LogRecord $record): string
    {
        try {
            $context = $record->context;

            // Extract standard fields
            $timestamp = $record->datetime->format('Y-m-d\TH:i:s.uP');
            $level = strtolower($record->level->getName());

            // 1. Event naming
            $event = $context['event'] ?? null;
            if (empty($event)) {
                if (preg_match('/^[a-z0-9_.-]+$/i', $record->message)) {
                    $event = $record->message;
                } else {
                    $event = $record->channel.'_'.$level;
                }
            }

            // 2. Request ID (Correlation ID)
            $shared = method_exists(Log::class, 'sharedContext') ? Log::sharedContext() : [];
            $requestId = $context['request_id']
                ?? $record->extra['request_id']
                ?? ($shared['request_id'] ?? null);

            if (empty($requestId) && app()->bound('request')) {
                $req = request();
                if ($req) {
                    $requestId = $req->header('X-Request-ID') ?? $req->attributes->get('request_id');
                }
            }

            // 3. Environment
            $environment = $context['environment']
                ?? ($shared['environment'] ?? null)
                ?? config('app.env', 'production');

            // 4. User ID
            $userId = $context['user_id']
                ?? ($shared['user_id'] ?? null);
            if ($userId === null && Auth::check()) {
                $userId = Auth::id();
            }

            // 5. IP Address
            $ipAddress = $context['ip_address']
                ?? $context['ip']
                ?? ($shared['ip_address'] ?? null);
            if (empty($ipAddress) && ! app()->runningInConsole() && app()->bound('request')) {
                $ipAddress = request()?->ip();
            }

            // 6. Duration in ms
            $durationMs = isset($context['duration_ms']) ? (float) $context['duration_ms'] : null;

            // 7. Context (cleaned from duplicate standard fields & sanitized)
            $cleanContext = $context;
            unset(
                $cleanContext['event'],
                $cleanContext['request_id'],
                $cleanContext['environment'],
                $cleanContext['user_id'],
                $cleanContext['ip_address'],
                $cleanContext['ip'],
                $cleanContext['duration_ms']
            );

            $sanitizedContext = $this->sanitizer->sanitizeArray($cleanContext);

            // Assemble structured payload
            $payload = [
                'timestamp' => $timestamp,
                'level' => $level,
                'event' => $event,
                'message' => $record->message,
                'request_id' => $requestId,
                'environment' => $environment,
                'user_id' => $userId,
                'ip_address' => $ipAddress,
                'duration_ms' => $durationMs,
                'context' => (object) $sanitizedContext,
            ];

            return json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)."\n";
        } catch (\Throwable $e) {
            // Fail-safe fallback so logging failure never crashes application
            $fallback = [
                'timestamp' => date('c'),
                'level' => 'error',
                'event' => 'logging_formatter_error',
                'message' => $record->message,
                'error' => $e->getMessage(),
            ];

            return json_encode($fallback)."\n";
        }
    }

    /**
     * Formats a set of log records.
     */
    public function formatBatch(array $records): string
    {
        $output = '';
        foreach ($records as $record) {
            $output .= $this->format($record);
        }

        return $output;
    }
}

