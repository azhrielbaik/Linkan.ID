<?php

use App\Http\Middleware\CheckSuspended;
use App\Http\Middleware\Localization;
use App\Http\Middleware\LogHttpRequests;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\AdminIdleTimeout;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
        $middleware->validateCsrfTokens(except: [
            'midtrans/callback',
            'midtrans-callback'
        ]);
        $middleware->web(prepend: [
            Localization::class,
        ], append: [
            CheckSuspended::class,
            LogHttpRequests::class,
        ]);
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'check.suspended' => CheckSuspended::class,
            'admin.timeout' => AdminIdleTimeout::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->report(function (Throwable $e) {
            // Exclude standard operational/client exceptions from error logs
            if ($e instanceof HttpResponseException
                || $e instanceof ValidationException
                || $e instanceof AuthenticationException
                || $e instanceof NotFoundHttpException
                || $e instanceof MethodNotAllowedHttpException) {
                return false;
            }

            $request = app()->runningInConsole() ? null : request();
            $sensitiveFields = [
                'password',
                'password_confirmation',
                'otp',
                'otp_code',
                'token',
                'secret',
                'account_number',
                'server_key',
                'client_key',
            ];

            $input = [];
            if ($request) {
                $input = $request->all();
                array_walk_recursive($input, function (&$value, $key) use ($sensitiveFields) {
                    if (in_array(strtolower((string) $key), $sensitiveFields, true)) {
                        $value = '******';
                    }
                });
            }

            $traceLines = array_slice(explode("\n", $e->getTraceAsString()), 0, 10);

            Log::channel('errors')->error($e->getMessage(), [
                'exception_class' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'url' => $request ? $request->fullUrl() : 'console',
                'method' => $request ? $request->method() : 'CLI',
                'ip' => $request ? $request->ip() : '127.0.0.1',
                'user_id' => Auth::check() ? Auth::id() : null,
                'user_agent' => $request ? $request->userAgent() : 'CLI',
                'input' => $input,
                'trace' => $traceLines,
            ]);

            return false;
        });
    })->create();
