<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test successful public profile rendering when appearance is null
     */
    public function test_public_profile_renders_successfully_without_appearance(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser',
        ]);

        $response = $this->get(route('track.view', ['username' => $user->username]));

        $response->assertStatus(200);
        $response->assertSee($user->name);
    }

    /**
     * Test successful public profile rendering when appearance exists
     */
    public function test_public_profile_renders_successfully_with_appearance(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser2',
        ]);

        \App\Models\Appearance::create([
            'user_id' => $user->id,
            'name' => 'Custom Display Name',
            'bio' => 'This is my custom bio',
            'theme_color' => '#123456',
        ]);

        $response = $this->get(route('public.profile', ['username' => $user->username]));

        $response->assertStatus(200);
        $response->assertSee('Custom Display Name');
        $response->assertSee('This is my custom bio');
    }

    /**
     * Test that link views are tracked successfully
     */
    public function test_public_profile_tracks_link_views(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser3',
        ]);

        // First visit should insert a view
        $this->get(route('track.view', ['username' => $user->username]));

        $this->assertDatabaseHas('link_views', [
            'user_id' => $user->id,
            'link_id' => $user->username,
        ]);

        $initialViewCount = \DB::table('link_views')->where('link_id', $user->username)->count();

        // Second visit from same IP/UserAgent on same day should NOT insert another view
        $this->get(route('track.view', ['username' => $user->username]));

        $newViewCount = \DB::table('link_views')->where('link_id', $user->username)->count();

        $this->assertEquals($initialViewCount, $newViewCount);
    }
}
