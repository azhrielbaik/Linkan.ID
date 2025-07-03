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
public function boot()
{
    \Midtrans\Config::$serverKey = env('SB-Mid-server-qbA7U8pOrHFCGy-0LlFclqIG');
    \Midtrans\Config::$isProduction = false; // true untuk production
    \Midtrans\Config::$isSanitized = true;
    \Midtrans\Config::$is3ds = true;
}

}
