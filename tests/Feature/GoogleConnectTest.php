<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Contracts\Provider as SocialiteProvider;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class GoogleConnectTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test tamu (unauthenticated) tidak dapat mengakses route google.connect
     */
    public function test_guest_cannot_access_google_connect(): void
    {
        $response = $this->get(route('google.connect'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test user yang terautentikasi dapat mengakses google.connect dan session intent diatur
     */
    public function test_authenticated_user_can_initiate_google_connect(): void
    {
        $user = User::factory()->create([
            'role' => 'admin_seller',
        ]);

        $mockProvider = Mockery::mock(SocialiteProvider::class);
        $mockProvider->shouldReceive('redirect')
            ->once()
            ->andReturn(redirect('https://accounts.google.com/o/oauth2/auth'));

        Socialite::shouldReceive('driver')
            ->with('google')
            ->once()
            ->andReturn($mockProvider);

        $response = $this->actingAs($user)->get(route('google.connect'));

        $response->assertSessionHas('google_intent', 'connect');
        $response->assertRedirect('https://accounts.google.com/o/oauth2/auth');
    }

    /**
     * Test gagal menghubungkan akun Google jika email Google berbeda dengan email akun user
     */
    public function test_google_connect_fails_when_email_mismatches(): void
    {
        $user = User::factory()->create([
            'email' => 'user@linkan.id',
            'role' => 'admin_seller',
        ]);

        $socialiteUser = new SocialiteUser;
        $socialiteUser->id = 'google-12345';
        $socialiteUser->email = 'different@gmail.com';
        $socialiteUser->name = 'Different Google User';

        $mockProvider = Mockery::mock(SocialiteProvider::class);
        $mockProvider->shouldReceive('user')
            ->once()
            ->andReturn($socialiteUser);

        Socialite::shouldReceive('driver')
            ->with('google')
            ->once()
            ->andReturn($mockProvider);

        $response = $this->actingAs($user)
            ->withSession(['google_intent' => 'connect'])
            ->get(route('google.callback'));

        $response->assertRedirect(route('admin.account'));
        $response->assertSessionHasErrors('google');

        $user->refresh();
        $this->assertNull($user->google_id);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => 'google_connect_failed',
        ]);
    }

    /**
     * Test gagal menghubungkan akun Google jika google_id sudah digunakan akun lain
     */
    public function test_google_connect_fails_when_google_id_already_taken(): void
    {
        User::factory()->create([
            'email' => 'other@linkan.id',
            'google_id' => 'google-already-taken',
            'role' => 'admin_seller',
        ]);

        $currentUser = User::factory()->create([
            'email' => 'current@linkan.id',
            'google_id' => null,
            'role' => 'admin_seller',
        ]);

        $socialiteUser = new SocialiteUser;
        $socialiteUser->id = 'google-already-taken';
        $socialiteUser->email = 'current@linkan.id';
        $socialiteUser->name = 'Current User';

        $mockProvider = Mockery::mock(SocialiteProvider::class);
        $mockProvider->shouldReceive('user')
            ->once()
            ->andReturn($socialiteUser);

        Socialite::shouldReceive('driver')
            ->with('google')
            ->once()
            ->andReturn($mockProvider);

        $response = $this->actingAs($currentUser)
            ->withSession(['google_intent' => 'connect'])
            ->get(route('google.callback'));

        $response->assertRedirect(route('admin.account'));
        $response->assertSessionHasErrors('google');

        $currentUser->refresh();
        $this->assertNull($currentUser->google_id);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $currentUser->id,
            'action' => 'google_connect_failed',
        ]);
    }

    /**
     * Test berhasil menghubungkan akun Google jika email cocok
     */
    public function test_google_connect_success_when_email_matches(): void
    {
        $user = User::factory()->create([
            'email' => 'seller@linkan.id',
            'google_id' => null,
            'role' => 'admin_seller',
        ]);

        $socialiteUser = new SocialiteUser;
        $socialiteUser->id = 'google-unique-999';
        $socialiteUser->email = 'seller@linkan.id';
        $socialiteUser->name = 'Seller User';

        $mockProvider = Mockery::mock(SocialiteProvider::class);
        $mockProvider->shouldReceive('user')
            ->once()
            ->andReturn($socialiteUser);

        Socialite::shouldReceive('driver')
            ->with('google')
            ->once()
            ->andReturn($mockProvider);

        $response = $this->actingAs($user)
            ->withSession(['google_intent' => 'connect'])
            ->get(route('google.callback'));

        $response->assertRedirect(route('admin.account'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertSame('google-unique-999', $user->google_id);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => 'google_connected',
        ]);
    }
}
