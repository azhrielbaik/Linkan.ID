<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AssignRequestId
{
    public const HEADER_NAME = 'X-Request-ID';

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $inboundId = $request->header(self::HEADER_NAME);

        // Sanitize or generate correlation UUID
        if (is_string($inboundId) && preg_match('/^[a-zA-Z0-9_\-\.]{8,64}$/', $inboundId)) {
            $requestId = $inboundId;
        } else {
            $requestId = (string) Str::uuid();
        }

        // Attach to request attributes for downstream handlers and exceptions
        $request->attributes->set('request_id', $requestId);

        // Propagate to Monolog shared logger context
        try {
            if (method_exists(Log::class, 'shareContext')) {
                Log::shareContext([
                    'request_id' => $requestId,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent() ?: 'unknown',
                    'environment' => config('app.env', 'production'),
                ]);
            }
        } catch (\Throwable) {
            // Fail-safe: context sharing must not interrupt request processing
        }

        $response = $next($request);

        // Propagate correlation ID to outbound response headers
        try {
            $response->headers->set(self::HEADER_NAME, $requestId);
        } catch (\Throwable) {
            // Fail-safe
        }

        return $response;
    }
}

