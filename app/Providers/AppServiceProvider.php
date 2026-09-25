<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
  // use Midtrans\Config;
public function boot(): void
{
    if ($this->app->environment('production') || request()->header('X-Forwarded-Proto') === 'https' || request()->server('HTTP_X_FORWARDED_PROTO') === 'https') {
        \Illuminate\Support\Facades\URL::forceScheme('https');
    }

    if ($this->app->runningInConsole()) {
        \Illuminate\Support\Facades\URL::defaults(['locale' => 'id']);
    }

    \Midtrans\Config::$serverKey = env('SB-Mid-server-qbA7U8pOrHFCGy-0LlFclqIG');
    \Midtrans\Config::$isProduction = false; // true untuk production
    \Midtrans\Config::$isSanitized = true;
    \Midtrans\Config::$is3ds = true;

    \Illuminate\Pagination\Paginator::defaultView('platformadmin.partials.pagination');
    \Illuminate\Pagination\Paginator::defaultSimpleView('platformadmin.partials.pagination');

    \Illuminate\Pagination\CursorPaginator::currentCursorResolver(function ($cursorName = 'cursor', $default = null) {
        $raw = request()->input($cursorName, $default);
        return \App\Support\Pagination\EncryptedCursorPaginator::decryptCursor($raw);
    });

    $this->app->bind(
        \Illuminate\Pagination\CursorPaginator::class,
        \App\Support\Pagination\EncryptedCursorPaginator::class
    );
}

}
