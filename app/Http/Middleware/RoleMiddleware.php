<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Mengecek apakah user yang login memiliki role yang sesuai.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles  Role yang diizinkan (bisa lebih dari satu)
     */
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->role;

        if (!in_array($userRole, $roles)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk halaman ini.');
        }

        return $next($request);
    }
}
