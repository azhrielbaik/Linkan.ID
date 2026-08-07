<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test successful user registration.
     */
    public function test_user_can_register_successfully(): void
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // It should redirect to login page with a success message
        $response->assertRedirect('/login');
        $response->assertSessionHas('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');

        // Check if user was actually created in database
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'johndoe@example.com',
            'role' => 'admin_seller',
            'is_link_active' => 1,
        ]);

        // Verify password is hashed
        $user = User::where('email', 'johndoe@example.com')->first();
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    /**
     * Test registration fails with invalid data.
     */
    public function test_registration_fails_with_invalid_data(): void
    {
        $response = $this->post('/register', [
            'name' => '', // Name is required
            'username' => 'jd', // Username is too short (min 3)
            'email' => 'invalid-email', // Invalid email format
            'password' => 'pass', // Password is too short (min 8)
            'password_confirmation' => 'different', // Password does not match
        ]);

        $response->assertSessionHasErrors(['name', 'username', 'email', 'password']);
        $this->assertDatabaseMissing('users', [
            'username' => 'jd',
        ]);
    }

    /**
     * Test username and email must be unique.
     */
    public function test_username_and_email_must_be_unique(): void
    {
        User::factory()->create([
            'username' => 'existinguser',
            'email' => 'existing@example.com',
        ]);

        $response = $this->post('/register', [
            'name' => 'Another User',
            'username' => 'existinguser', // Duplicate
            'email' => 'existing@example.com', // Duplicate
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['username', 'email']);
    }
}
