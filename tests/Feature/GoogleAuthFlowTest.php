<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Contracts\Provider as SocialiteProvider;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class GoogleAuthFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test unregistered user choosing Google Login is blocked and redirected to register page.
     */
    public function test_unregistered_user_cannot_login_with_google_and_redirects_to_register(): void
    {
        $socialiteUser = new SocialiteUser;
        $socialiteUser->id = 'google-new-999';
        $socialiteUser->name = 'Budi Santoso';
        $socialiteUser->email = 'budi.santoso@example.com';

        $mockProvider = Mockery::mock(SocialiteProvider::class);
        $mockProvider->shouldReceive('user')
            ->once()
            ->andReturn($socialiteUser);

        Socialite::shouldReceive('driver')
            ->with('google')
            ->once()
            ->andReturn($mockProvider);

        // Initiate from login page
        $response = $this->withSession(['google_intent' => 'login'])
            ->get(route('google.callback'));

        // Assert redirected to register page with warning
        $response->assertRedirect(route('register'));
        $response->assertSessionHas('warning', 'Akun Google Anda belum terdaftar. Silakan lakukan pendaftaran akun terlebih dahulu.');
        $response->assertSessionHas('google_data', [
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@example.com',
            'google_id' => 'google-new-999',
        ]);

        // Assert user was NOT automatically created in the database
        $this->assertDatabaseMissing('users', [
            'email' => 'budi.santoso@example.com',
        ]);
        $this->assertGuest();
    }

    /**
     * Test registered user can log in with Google normally.
     */
    public function test_registered_user_can_login_with_google(): void
    {
        $user = User::factory()->create([
            'email' => 'existing@example.com',
            'google_id' => 'google-existing-123',
            'role' => 'admin_seller',
        ]);

        $socialiteUser = new SocialiteUser;
        $socialiteUser->id = 'google-existing-123';
        $socialiteUser->name = $user->name;
        $socialiteUser->email = $user->email;

        $mockProvider = Mockery::mock(SocialiteProvider::class);
        $mockProvider->shouldReceive('user')
            ->once()
            ->andReturn($socialiteUser);

        Socialite::shouldReceive('driver')
            ->with('google')
            ->once()
            ->andReturn($mockProvider);

        $response = $this->withSession(['google_intent' => 'login'])
            ->get(route('google.callback'));

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test user registering with Google data completes registration form and can subsequently login with Google.
     */
    public function test_user_completes_registration_form_with_google_id_then_logs_in(): void
    {
        // 1. Submit registration form with google_id
        $registerResponse = $this->post(route('register.submit'), [
            'name' => 'Budi Santoso',
            'username' => 'budisantoso',
            'email' => 'budi.santoso@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'google_id' => 'google-budi-777',
        ]);

        $registerResponse->assertRedirect(route('admin.dashboard'));
        $this->assertDatabaseHas('users', [
            'email' => 'budi.santoso@example.com',
            'google_id' => 'google-budi-777',
            'username' => 'budisantoso',
        ]);

        $createdUser = User::where('email', 'budi.santoso@example.com')->first();
        $this->assertNotNull($createdUser);

        // Logout
        $this->post(route('logout'));
        $this->assertGuest();

        // 2. Now login with Google as registered user
        $socialiteUser = new SocialiteUser;
        $socialiteUser->id = 'google-budi-777';
        $socialiteUser->name = 'Budi Santoso';
        $socialiteUser->email = 'budi.santoso@example.com';

        $mockProvider = Mockery::mock(SocialiteProvider::class);
        $mockProvider->shouldReceive('user')
            ->once()
            ->andReturn($socialiteUser);

        Socialite::shouldReceive('driver')
            ->with('google')
            ->once()
            ->andReturn($mockProvider);

        $loginResponse = $this->withSession(['google_intent' => 'login'])
            ->get(route('google.callback'));

        $loginResponse->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($createdUser);
    }

    /**
     * Test register route sets intent to register.
     */
    public function test_google_register_route_sets_intent_to_register(): void
    {
        $mockProvider = Mockery::mock(SocialiteProvider::class);
        $mockProvider->shouldReceive('redirect')
            ->once()
            ->andReturn(redirect('https://accounts.google.com/o/oauth2/auth'));

        Socialite::shouldReceive('driver')
            ->with('google')
            ->once()
            ->andReturn($mockProvider);

        $response = $this->get(route('google.register'));

        $response->assertSessionHas('google_intent', 'register');
        $response->assertRedirect('https://accounts.google.com/o/oauth2/auth');
    }

    /**
     * Test registering via Google automatically creates an account using the Google profile name and redirects to dashboard.
     */
    public function test_google_register_creates_account_with_google_name_and_redirects_to_dashboard(): void
    {
        $socialiteUser = new SocialiteUser;
        $socialiteUser->id = 'google-reg-101';
        $socialiteUser->name = 'Siti Nurhaliza';
        $socialiteUser->email = 'siti.nurhaliza@example.com';

        $mockProvider = Mockery::mock(SocialiteProvider::class);
        $mockProvider->shouldReceive('user')
            ->once()
            ->andReturn($socialiteUser);

        Socialite::shouldReceive('driver')
            ->with('google')
            ->once()
            ->andReturn($mockProvider);

        $response = $this->withSession(['google_intent' => 'register'])
            ->get(route('google.callback'));

        // Assert redirect directly to seller dashboard with success flash
        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('success');

        // Assert user was created in database with the Google name
        $this->assertDatabaseHas('users', [
            'email' => 'siti.nurhaliza@example.com',
            'name' => 'Siti Nurhaliza',
            'google_id' => 'google-reg-101',
            'role' => 'admin_seller',
        ]);

        $createdUser = User::where('email', 'siti.nurhaliza@example.com')->first();
        $this->assertNotNull($createdUser);
        $this->assertEquals('Siti Nurhaliza', $createdUser->name);
        $this->assertAuthenticatedAs($createdUser);

        // Verify dashboard displays profile name
        $dashboardResponse = $this->actingAs($createdUser)->get(route('admin.dashboard'));
        $dashboardResponse->assertOk();
        $dashboardResponse->assertSee('Siti Nurhaliza');
    }

    /**
     * Test registering via Google for an existing user updates google_id, logs them in, and redirects to dashboard.
     */
    public function test_google_register_existing_user_logs_in_and_redirects_to_dashboard(): void
    {
        $user = User::factory()->create([
            'email' => 'citra@example.com',
            'name' => 'Citra Lestari',
            'google_id' => null,
            'role' => 'admin_seller',
        ]);

        $socialiteUser = new SocialiteUser;
        $socialiteUser->id = 'google-citra-555';
        $socialiteUser->name = 'Citra Lestari';
        $socialiteUser->email = 'citra@example.com';

        $mockProvider = Mockery::mock(SocialiteProvider::class);
        $mockProvider->shouldReceive('user')
            ->once()
            ->andReturn($socialiteUser);

        Socialite::shouldReceive('driver')
            ->with('google')
            ->once()
            ->andReturn($mockProvider);

        $response = $this->withSession(['google_intent' => 'register'])
            ->get(route('google.callback'));

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);

        $user->refresh();
        $this->assertEquals('google-citra-555', $user->google_id);
    }
}
