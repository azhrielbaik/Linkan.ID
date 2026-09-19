<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::create('/register', 'POST', [
    'name' => 'Test User',
    'username' => 'testuser123',
    'email' => 'testuser123@example.com',
    'password' => 'password123',
    'password_confirmation' => 'password123',
    'google_id' => '1234567890',
    '_token' => csrf_token(),
]);

// Since CSRF token check is active, let's bypass it for this test or just use User::create
// Actually let's just see if User::create throws an error
try {
    $user = \App\Models\User::create([
        'name' => 'Test User',
        'username' => 'testuser123',
        'email' => 'testuser123@example.com',
        'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        'is_link_active' => true,
        'role' => 'admin_seller',
        'google_id' => '1234567890'
    ]);
    echo "User created successfully. ID: " . $user->id . "\n";
    $user->delete(); // cleanup
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
