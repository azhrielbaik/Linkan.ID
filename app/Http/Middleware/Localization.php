<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->segment(1);

        if (in_array($locale, ['en', 'id'])) {
            App::setLocale($locale);
            \Illuminate\Support\Facades\URL::defaults(['locale' => $locale]);
            session()->put('locale', $locale); // Keep backup for routes without prefix
        } else {
            // For routes without the {locale} prefix (like shortlinks)
            $fallbackLocale = session()->get('locale', 'id');
            App::setLocale($fallbackLocale);
            \Illuminate\Support\Facades\URL::defaults(['locale' => $fallbackLocale]);
        }

        return $next($request);
    }
}
