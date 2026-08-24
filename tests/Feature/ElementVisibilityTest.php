<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\ImageElement;

class ElementVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_toggle_element_visibility()
    {
        // Setup User and Element
        $user = User::factory()->create();
        $element = ImageElement::create([
            'user_id' => $user->id,
            'image_path' => 'dummy/path.png',
            'order_position' => 1,
            'is_active' => true
        ]);

        // Assert Initial State
        $this->assertTrue($element->fresh()->is_active);

        // Make Request
        $response = $this->actingAs($user)->postJson('/admin/elements/toggle-visibility', [
            'element_type' => 'image',
            'element_id' => $element->id,
            'is_active' => false
        ]);

        // Assert Response
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // Assert Database Updated
        $this->assertFalse($element->fresh()->is_active);
    }
}
