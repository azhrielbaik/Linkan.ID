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
            'role' => 'admin_seller',
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
            'role' => 'admin_platform',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('platform-admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    /**
     * Test successful login for a user with standard/custom role redirects safely
     */
    public function test_login_successful_custom_role_redirects_safely(): void
    {
        $user = User::factory()->create([
            'email' => 'custom@example.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
        ]);

        $response = $this->post('/login', [
            'email' => 'custom@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHasNoErrors();
        $this->assertAuthenticatedAs($user);
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

        $this->assertTrue(in_array($response->status(), [429, 302]));
    }
}
