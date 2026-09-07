<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

Route::get('/{slug}', function() {})->name('shortlink.redirect');
Route::get('/{username}', function() {})->name('public.profile');

echo "shortlink: " . route('shortlink.redirect', ['slug' => 'test-short']) . "\n";
echo "profile: " . route('public.profile', ['username' => 'test-profile']) . "\n";
