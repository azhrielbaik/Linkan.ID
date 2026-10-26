<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PublicDisputeController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\PublicReviewController;
use App\Http\Controllers\PublicShortlinkController;
use Illuminate\Support\Facades\Route;

// Welcome & Static pages
Route::get('/', fn () => view('public.pages.welcome'))->name('welcome');
Route::view('/pricing', 'pricing')->name('pricing');
Route::view('/faq', 'public.pages.faq')->name('FAQ');
Route::view('/about', 'public.pages.about')->name('about');

// Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact.form');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// Public microsite & link tracking
Route::get('/linkan.id/{username}', [PublicPageController::class, 'show'])->name('track.view');

// Public product & checkout
Route::get('/product/{id}', [CheckoutController::class, 'show'])->name('product.show');
Route::match(['get', 'post'], '/checkout/{id}', [CheckoutController::class, 'checkout'])->name('checkout');
Route::get('/checkout/{id}/success', [CheckoutController::class, 'checkoutSuccess'])->name('checkout.success');
Route::post('/cart/update-qty', [CheckoutController::class, 'updateQty'])->name('cart.updateQty');
Route::post('/transaction/store', [CheckoutController::class, 'storeTransaction'])->name('transaction.store');

// Layanan Pengaduan Sengketa & Refund Pembeli
Route::get('/bantuan/sengketa', [PublicDisputeController::class, 'create'])->name('public.dispute.create');
Route::post('/bantuan/sengketa/check-order', [PublicDisputeController::class, 'checkOrder'])->middleware('throttle:30,1')->name('public.dispute.check_order');
Route::post('/bantuan/sengketa', [PublicDisputeController::class, 'store'])->middleware('throttle:10,1')->name('public.dispute.store');

// Review Produk Digital (Token-based)
Route::get('/review/{token}', [PublicReviewController::class, 'show'])->middleware('throttle:10,1')->name('public.review.show');
Route::post('/review/{token}', [PublicReviewController::class, 'store'])->middleware('throttle:5,1')->name('public.review.store');

// Password-protected shortlink
Route::get('/p/{slug}', [PublicShortlinkController::class, 'passwordForm'])->name('shortlink.password.form');
Route::post('/p/{slug}', [PublicShortlinkController::class, 'verifyPassword'])->name('shortlink.password.verify');
