<?php

namespace Tests\Feature;

use App\Models\Shortlink;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShortlinkDetailAndAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_shortlink_with_title_and_description()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('shortlink.store'), [
            'title' => 'My Awesome Link',
            'description' => 'This is a test description for my shortlink.',
            'slug' => 'awesome-link',
            'destination' => 'https://example.com/very-long-url',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('shortlinks', [
            'user_id' => $user->id,
            'title' => 'My Awesome Link',
            'description' => 'This is a test description for my shortlink.',
            'slug' => 'awesome-link',
            'destination' => 'https://example.com/very-long-url',
        ]);
    }

    public function test_shortlink_list_displays_title_and_description()
    {
        $user = User::factory()->create();
        $shortlink = Shortlink::create([
            'user_id' => $user->id,
            'title' => 'Test Link Title',
            'description' => 'Test Link Description',
            'slug' => 'test-link',
            'destination' => 'https://example.com',
        ]);

        $response = $this->actingAs($user)->get(route('shortlink.index'));

        $response->assertStatus(200);
        $response->assertSee('Test Link Title');
        $response->assertSee('Test Link Description');
        $response->assertSee('test-link');
    }
}
