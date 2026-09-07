<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminIdleTimeout
{
    /**
     * Durasi timeout tidak aktif dalam detik (10 menit = 600 detik).
     */
    const IDLE_TIMEOUT_SECONDS = 600;

    /**
     * Handle an incoming request.
     * Memeriksa apakah admin platform sudah idle tanpa aktivitas selama lebih dari 10 menit.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'admin_platform') {
            $lastActivity = session('admin_last_activity_time');
            $now = now()->timestamp;

            if ($lastActivity && ($now - $lastActivity) > self::IDLE_TIMEOUT_SECONDS) {
                // Catat Log jika ActivityLogger tersedia
                if (class_exists(\App\Services\ActivityLogger::class)) {
                    \App\Services\ActivityLogger::log(
                        'admin_session_timeout',
                        "Sesi Platform Admin berakhir otomatis karena tidak ada aktivitas selama 10 menit (" . Auth::user()->email . ")",
                        [
                            'user_id' => Auth::id(),
                            'idle_seconds' => $now - $lastActivity,
                            'timeout_limit' => self::IDLE_TIMEOUT_SECONDS,
                        ]
                    );
                }

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'status' => 'session_timeout',
                        'message' => 'Sesi Anda telah berakhir karena tidak ada aktivitas selama 10 menit. Silakan masuk kembali.',
                        'redirect' => route('login'),
                    ], 401);
                }

                return redirect()->route('login')->with('warning', 'Sesi Anda telah berakhir karena tidak ada aktivitas selama 10 menit. Silakan masuk kembali.');
            }

            // Pengecualian: jangan perbarui timestamp jika request adalah background SSE / polling berkala
            // agar background fetch otomatis tidak mereset timer saat admin tidak aktif di browser
            $isBackgroundPoll = $request->is('platform-admin/notifications*') 
                || $request->is('platform-admin/commissions*')
                || $request->header('X-Background-Ping') === 'true';

            if (!$isBackgroundPoll) {
                session(['admin_last_activity_time' => $now]);
            }
        }

        return $next($request);
    }
}
