<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Appearance;
use App\Models\DigitalProduct;

class DigitalProductStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_digital_product_is_added_to_blocks_order_upon_creation()
    {
        $user = User::factory()->create();
        
        // Create an appearance (microsite)
        $appearance = Appearance::create([
            'user_id' => $user->id,
            'name' => 'Test Microsite',
            'alias' => 'test-microsite',
            'blocks_order' => 'profile'
        ]);

        // Submit to create a new digital product
        $response = $this->actingAs($user)->postJson(route('admin.elements.digital-product.store'), [
            'title' => 'Test Digital Product',
            'description' => 'Test Description',
            'pricing_type' => 'fixed',
            'price_fixed' => 50000,
            'appearance_id' => $appearance->id,
            'deliverable_type' => 'other',
            'deliverable_url' => 'https://example.com'
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        // Assert product is created
        $product = DigitalProduct::first();
        $this->assertNotNull($product);
        $this->assertEquals('Test Digital Product', $product->title);

        // Assert product ID is appended to blocks_order
        $appearance->refresh();
        $this->assertEquals('profile,digitalproduct_' . $product->id, $appearance->blocks_order);
    }
}
