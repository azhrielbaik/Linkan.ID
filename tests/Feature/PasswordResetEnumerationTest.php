<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordResetEnumerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_does_not_enumerate_unregistered_email(): void
    {
        $existingUser = User::factory()->create([
            'email' => 'registered_user@example.com',
        ]);

        // 1. Submit existing email
        $responseExisting = $this->post(route('password.request-otp'), [
            'email' => 'registered_user@example.com',
        ]);

        // 2. Submit non-existing email
        $responseNonExisting = $this->post(route('password.request-otp'), [
            'email' => 'unregistered_random_user@example.com',
        ]);

        // Both must redirect to the verify-otp route to prevent email enumeration
        $responseExisting->assertRedirect();
        $responseNonExisting->assertRedirect();

        $this->assertStringContainsString('verify-otp', (string) $responseExisting->headers->get('Location'));
        $this->assertStringContainsString('verify-otp', (string) $responseNonExisting->headers->get('Location'));
    }

    public function test_registered_user_can_view_verify_otp_page(): void
    {
        $user = User::factory()->create([
            'email' => 'legitimate_user@example.com',
        ]);

        $this->post(route('password.request-otp'), [
            'email' => 'legitimate_user@example.com',
        ]);

        $token = session('reset_token');
        $this->assertNotEmpty($token);

        $response = $this->get(route('password.verify-otp', ['token' => $token]));
        $response->assertStatus(200);
        $response->assertViewIs('auth.verify-otp');
    }

    public function test_unregistered_email_can_view_verify_otp_page_without_breaking(): void
    {
        $this->post(route('password.request-otp'), [
            'email' => 'ghost_unregistered@example.com',
        ]);

        $token = session('reset_token');
        $this->assertNotEmpty($token);

        $response = $this->get(route('password.verify-otp', ['token' => $token]));
        $response->assertStatus(200);
        $response->assertViewIs('auth.verify-otp');
    }
}
