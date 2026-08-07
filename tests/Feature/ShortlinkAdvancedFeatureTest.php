<?php

namespace Tests\Feature;

use App\Models\Shortlink;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShortlinkAdvancedFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_update_shortlink_slug_password_and_expiry()
    {
        $user = User::factory()->create();
        $shortlink = Shortlink::create([
            'user_id' => $user->id,
            'slug' => 'old-slug',
            'destination' => 'https://example.com'
        ]);

        $response = $this->actingAs($user)->put(route('shortlink.update', $shortlink), [
            'slug' => 'new-slug',
            'password' => 'secret123',
            'expires_at' => now()->addDays(2)->format('Y-m-d\TH:i'),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $shortlink->refresh();
        $this->assertEquals('new-slug', $shortlink->slug);
        $this->assertEquals('secret123', $shortlink->password);
        $this->assertNotNull($shortlink->expires_at);
    }

    public function test_expired_shortlink_returns_410()
    {
        $user = User::factory()->create();
        $shortlink = Shortlink::create([
            'user_id' => $user->id,
            'slug' => 'expired-link',
            'destination' => 'https://example.com',
            'expires_at' => now()->subDay(),
        ]);

        $response = $this->get('/expired-link');
        $response->assertStatus(410);
    }

    public function test_password_protected_shortlink_redirects_to_form()
    {
        $user = User::factory()->create();
        $shortlink = Shortlink::create([
            'user_id' => $user->id,
            'slug' => 'locked-link',
            'destination' => 'https://example.com',
            'password' => 'secret123'
        ]);

        $response = $this->get('/locked-link');
        $response->assertRedirect(route('shortlink.password.form', 'locked-link'));
    }

    public function test_can_unlock_password_protected_shortlink()
    {
        $user = User::factory()->create();
        $shortlink = Shortlink::create([
            'user_id' => $user->id,
            'slug' => 'locked-link-2',
            'destination' => 'https://example.com',
            'password' => 'secret123'
        ]);

        $response = $this->post(route('shortlink.password.verify', 'locked-link-2'), [
            'password' => 'secret123'
        ]);

        $response->assertRedirect('/locked-link-2');
        $this->assertTrue(session()->has('unlocked_shortlink_' . $shortlink->id));
        
        // After unlocking, hitting the link directly should redirect to destination
        $redirectResponse = $this->get('/locked-link-2');
        $redirectResponse->assertRedirect('https://example.com');
    }
}
