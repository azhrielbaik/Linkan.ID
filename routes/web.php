<?php

use App\Http\Controllers\AdminSeller\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| External Callbacks & Utilities (No locale prefix required)
|--------------------------------------------------------------------------
*/
Route::get('/track-click', [DashboardController::class, 'trackClick'])->name('track.click');
Route::post('/midtrans/callback', [CheckoutController::class, 'midtransCallback'])->middleware('throttle:30,1')->name('midtrans.callback');

// Switch Language Route (Redirects to new locale)
Route::get('/lang/{lang}', function ($lang) {
    if (in_array($lang, ['en', 'id'])) {
        $referer = request()->headers->get('referer');
        if ($referer) {
            $parsed = parse_url($referer);
            $path = $parsed['path'] ?? '/';
            $segments = explode('/', ltrim($path, '/'));

            if (isset($segments[0]) && in_array($segments[0], ['id', 'en'])) {
                $segments[0] = $lang;
                $newPath = '/'.implode('/', $segments);
                $query = isset($parsed['query']) ? '?'.$parsed['query'] : '';

                return redirect($newPath.$query);
            }
        }

        return redirect('/'.$lang);
    }

    return redirect()->back();
})->name('lang.switch');

/*
|--------------------------------------------------------------------------
| Non-prefixed POST Fallbacks for Auth & Cart Actions (Testing & Compatibility)
|--------------------------------------------------------------------------
*/
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/cart/update-qty', [CheckoutController::class, 'updateQty']);
Route::post('/transaction/store', [CheckoutController::class, 'storeTransaction']);
Route::post('/admin/elements/toggle-visibility', [\App\Http\Controllers\AdminSeller\Microsite\ElementVisibilityController::class, 'toggle'])->middleware(['auth']);

/*
|--------------------------------------------------------------------------
| Localized Application Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => '{locale}', 'where' => ['locale' => 'id|en']], function () {
    require __DIR__.'/web/public.php';
    require __DIR__.'/web/auth.php';
    require __DIR__.'/web/admin_seller.php';
    require __DIR__.'/web/platform_admin.php';
});

/*
|--------------------------------------------------------------------------
| Centralized Fallback Redirect for Standard App Paths without Locale Prefix
|--------------------------------------------------------------------------
| Automatically redirects unprefixed standard routes (e.g., /login, /pricing,
| /checkout/{id}) to /{locale}/{path} while leaving user shortlinks untouched.
*/
Route::get('/', fn () => redirect('/'.session('locale', config('app.locale', 'id'))));

Route::get('/{prefix}/{subpath?}', function (Request $request, string $prefix, ?string $subpath = null) {
    $locale = session('locale', config('app.locale', 'id'));
    $target = $subpath ? "{$prefix}/{$subpath}" : $prefix;
    $query = $request->getQueryString();

    return redirect()->to("/{$locale}/{$target}".($query ? '?'.$query : ''));
})->where('prefix', 'login|register|checkout|product|bantuan|review|pricing|faq|about|contact|forgot-password|verify-otp|create-new-password|password-reset-success|admin|platform-admin|auth')
    ->where('subpath', '.*');

use App\Http\Controllers\PublicShortlinkController;

/*
|--------------------------------------------------------------------------
| Profile & Shortlink Catch-all Redirect — MUST remain last
|--------------------------------------------------------------------------
*/
Route::get('/{username}', [PublicShortlinkController::class, 'redirect'])->name('public.profile');
