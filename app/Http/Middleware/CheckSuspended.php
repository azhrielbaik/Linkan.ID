<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSuspended
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->isSuspended()) {
            // Abaikan jika platform admin
            if (Auth::user()->role === 'admin_platform') {
                return $next($request);
            }

            // Rute yang diperbolehkan untuk akun seller yang sedang disuspend
            $allowedRoutes = [
                'admin.dashboard',
                'admin.chart-data',
                'admin.appeal.store',
                'logout',
                'lang.switch',
            ];

            $currentRouteName = $request->route() ? $request->route()->getName() : null;

            if ($currentRouteName && in_array($currentRouteName, $allowedRoutes)) {
                return $next($request);
            }

            // Alihkan ke dashboard seller dengan pesan peringatan
            return redirect()->route('admin.dashboard')->with('error', 'Akun Anda sedang ditangguhkan (suspended). Fitur ini terkunci sementara.');
        }

        return $next($request);
    }
}
