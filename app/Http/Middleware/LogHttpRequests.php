<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogHttpRequests
{
    /**
     * Route names to exclude from logging due to frequent polling.
     *
     * @var array<int, string>
     */
    protected array $skipRoutes = [
        'admin.notifications.stream',
        'admin.notifications.index',
        'admin.chart-data',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);

        $response = $next($request);

        $durationMs = round((microtime(true) - $startTime) * 1000, 2);

        $routeName = $request->route() ? $request->route()->getName() : null;

        // Skip polling routes that produce high frequency log noise
        if ($routeName && in_array($routeName, $this->skipRoutes, true)) {
            return $response;
        }

        // Skip fast AJAX requests (duration < 500ms)
        if ($request->ajax() && $durationMs < 500) {
            return $response;
        }

        $statusCode = $response->getStatusCode();

        // Determine log level according to status code and duration
        if ($statusCode >= 500) {
            $level = 'error';
        } elseif ($statusCode >= 400 || $durationMs > 2000) {
            $level = 'warning';
        } else {
            $level = 'info';
        }

        Log::channel('daily')->log($level, 'HTTP Request Processed', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'route' => $routeName,
            'status' => $statusCode,
            'duration_ms' => $durationMs,
            'ip' => $request->ip(),
            'user_id' => Auth::check() ? Auth::id() : null,
            'user_agent' => $request->userAgent(),
        ]);

        return $response;
    }
}
