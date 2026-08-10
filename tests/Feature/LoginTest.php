<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test successful login for admin_seller role
     */
    public function test_login_successful_admin_seller(): void
    {
        $user = User::factory()->create([
            'email' => 'seller@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin_seller'
        ]);

        $response = $this->post('/login', [
            'email' => 'seller@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test successful login for admin_platform role
     */
    public function test_login_successful_admin_platform(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin_platform'
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('platform-admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    /**
     * Test login failure with incorrect password
     */
    public function test_login_fails_with_incorrect_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('correctpassword'),
        ]);

        $response = $this->post('/login', [
            'email' => 'user@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /**
     * Test login rate limiting
     */
    public function test_login_rate_limiting(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => 'user@example.com',
                'password' => 'wrongpassword',
            ]);
        }

        $response = $this->post('/login', [
            'email' => 'user@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(429);
    }
}
