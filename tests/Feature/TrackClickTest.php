<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\DigitalProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrackClickTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test successful tracking of a click.
     */
    public function test_can_track_click_successfully(): void
    {
        $user = User::factory()->create([
            'username' => 'testuserclick',
        ]);

        $product = new DigitalProduct();
        $product->user_id = $user->id;
        $product->title = 'Test Product';
        $product->price = 10000;
        $product->description = 'Test Description';
        $product->platform_type = 'other';
        $product->button_text = 'Beli Now';
        $product->verification_status = 'approved';
        $product->is_active = 1;
        $product->save();

        $targetUrl = route('product.show', $product->id);

        $response = $this->get(route('track.click', [
            'link_id' => $user->username,
            'target' => $targetUrl,
        ]), [
            'User-Agent' => 'TestBrowser/1.0',
        ]);

        $response->assertRedirect($targetUrl);

        $this->assertDatabaseHas('link_clicks', [
            'user_id' => $user->id,
            'link_id' => $user->username,
            'user_agent' => 'TestBrowser/1.0',
        ]);
    }

    /**
     * Test tracking click fails with invalid target.
     */
    public function test_tracking_click_fails_with_invalid_target(): void
    {
        $user = User::factory()->create([
            'username' => 'testuserclick2',
        ]);

        $response = $this->get(route('track.click', [
            'link_id' => $user->username,
            'target' => 'invalid-url',
        ]));

        $response->assertStatus(400);
    }
}
