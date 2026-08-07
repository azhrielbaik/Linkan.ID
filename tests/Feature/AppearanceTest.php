<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppearanceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test successful appearance update for a user.
     */
    public function test_user_can_update_appearance_including_social_links(): void
    {
        $user = User::factory()->create([
            'role' => 'admin_seller',
        ]);

        $response = $this->actingAs($user)->post(route('appearance.update'), [
            'name' => 'Custom Display Name',
            'bio' => 'This is a test bio',
            'theme_color' => '#ffffff',
            'instagram' => 'https://instagram.com/test',
            'tiktok' => 'https://tiktok.com/@test',
            'whatsapp' => 'https://wa.me/1234567890',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('appearances', [
            'user_id' => $user->id,
            'name' => 'Custom Display Name',
            'bio' => 'This is a test bio',
            'instagram' => 'https://instagram.com/test',
            'tiktok' => 'https://tiktok.com/@test',
            'whatsapp' => 'https://wa.me/1234567890',
        ]);
    }
}
