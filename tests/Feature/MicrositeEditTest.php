<?php

namespace Tests\Feature;

use App\Models\Appearance;
use App\Models\DigitalProduct;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MicrositeEditTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper to create an Appearance with required fields.
     */
    protected function createAppearance(User $user, array $overrides = []): Appearance
    {
        return Appearance::create(array_merge([
            'user_id' => $user->id,
            'name' => $user->name ?? 'Seller Store',
            'title' => 'My Test Microsite',
            'alias' => 'test-micro-' . uniqid(),
            'blocks_order' => 'profile,image_1,text_1,divider_1,digitalproduct_10',
            'theme_color' => '#000000',
            'font_family' => 'Arial',
            'background_color' => '#FFFFFF',
            'is_active' => true,
        ], $overrides));
    }

    /**
     * Helper to create a DigitalProduct with required fields.
     */
    protected function createProduct(User $user, array $overrides = []): DigitalProduct
    {
        return DigitalProduct::create(array_merge([
            'user_id' => $user->id,
            'title' => 'Sample Ebook',
            'price' => 50000,
            'platform_type' => 'other',
            'platform_url' => 'https://example.com/download',
            'button_text' => 'Beli Sekarang',
            'is_active' => true,
        ], $overrides));
    }

    /**
     * Test regression: Microsite edit page loads successfully when blocks_order is a string.
     */
    public function test_microsite_edit_mode_handles_string_blocks_order(): void
    {
        $user = User::factory()->create([
            'role' => 'admin_seller',
        ]);

        $appearance = $this->createAppearance($user, [
            'blocks_order' => 'profile,image_1,text_1,divider_1,digitalproduct_10',
        ]);

        $response = $this->actingAs($user)->get(route('admin.microsites.index', [
            'mode' => 'edit',
            'id' => $appearance->id,
        ]));

        $response->assertStatus(200);
        $response->assertViewIs('admin_seller.features.microsites.index');
        $response->assertViewHas('appearance');
    }

    /**
     * Test regression: Microsite edit page handles null or empty blocks_order gracefully.
     */
    public function test_microsite_edit_mode_handles_empty_blocks_order(): void
    {
        $user = User::factory()->create([
            'role' => 'admin_seller',
        ]);

        $appearance = $this->createAppearance($user, [
            'blocks_order' => null,
        ]);

        $response = $this->actingAs($user)->get(route('admin.microsites.index', [
            'mode' => 'edit',
            'id' => $appearance->id,
        ]));

        $response->assertStatus(200);
    }

    /**
     * Test regression: Pinning an existing product succeeds when blocks_order is a string.
     */
    public function test_pin_existing_product_handles_string_blocks_order(): void
    {
        $user = User::factory()->create([
            'role' => 'admin_seller',
        ]);

        $product = $this->createProduct($user);

        $appearance = $this->createAppearance($user, [
            'blocks_order' => 'profile,text_1',
        ]);

        $response = $this->actingAs($user)->postJson(route('admin.elements.digital-product.pin'), [
            'product_id' => $product->id,
            'appearance_id' => $appearance->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'id' => $product->id,
        ]);

        $appearance->refresh();
        $this->assertContains('digitalproduct_'.$product->id, $appearance->getBlocksOrderArray());
    }

    /**
     * Test regression: Destroying microsite handles string blocks_order without throwing.
     */
    public function test_destroy_microsite_handles_string_blocks_order(): void
    {
        $user = User::factory()->create([
            'role' => 'admin_seller',
        ]);

        $appearance = $this->createAppearance($user, [
            'blocks_order' => 'profile,image_2,text_2',
        ]);

        $response = $this->actingAs($user)->delete(route('admin.microsites.destroy', [
            'id' => $appearance->id,
        ]));

        $response->assertRedirect(route('admin.microsites.index', ['mode' => 'gallery']));
        $this->assertDatabaseMissing('appearances', ['id' => $appearance->id]);
    }
}
