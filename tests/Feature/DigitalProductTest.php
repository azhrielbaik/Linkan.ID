<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\DigitalProduct;

class DigitalProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_store_digital_product_fixed_price_success(): void
    {
        $response = $this->actingAs($this->user)->postJson(route('admin.elements.digital-product.store'), [
            'title' => 'E-Book Laravel Mastery',
            'description' => 'Panduan lengkap Laravel.',
            'pricing_type' => 'fixed',
            'price_fixed' => 150000,
            'quantity_min' => 1,
            'has_quantity_limit' => '0',
            'is_scheduled' => '0',
            'deliverable_type' => 'other',
            'deliverable_url' => 'https://example.com/download'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'title' => 'E-Book Laravel Mastery'
                 ]);

        $this->assertDatabaseHas('digital_products', [
            'title' => 'E-Book Laravel Mastery',
            'user_id' => $this->user->id,
            'pricing_type' => 'fixed',
            'price' => 150000
        ]);
    }

    public function test_store_digital_product_pwyw_success(): void
    {
        $response = $this->actingAs($this->user)->postJson(route('admin.elements.digital-product.store'), [
            'title' => 'Template Design',
            'description' => 'Template untuk desainer.',
            'pricing_type' => 'pwyw',
            'price_min' => 10000,
            'price_max' => 50000,
            'quantity_min' => 1,
            'has_quantity_limit' => '0',
            'is_scheduled' => '0',
            'deliverable_type' => 'other',
            'deliverable_url' => 'https://example.com/download'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'title' => 'Template Design'
                 ]);

        $this->assertDatabaseHas('digital_products', [
            'title' => 'Template Design',
            'user_id' => $this->user->id,
            'pricing_type' => 'pwyw',
            'price_min' => 10000,
            'price_max' => 50000
        ]);
    }

    public function test_store_digital_product_validation_error(): void
    {
        // title is required
        $response = $this->actingAs($this->user)->postJson(route('admin.elements.digital-product.store'), [
            'description' => 'Tanpa judul',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title']);
    }

    public function test_destroy_digital_product_success(): void
    {
        $product = DigitalProduct::create([
            'user_id' => $this->user->id,
            'title' => 'Produk Tes',
            'description' => 'Deskripsi',
            'pricing_type' => 'fixed',
            'price' => 10000,
            'quantity_min' => 1,
            'has_quantity_limit' => false,
            'is_scheduled' => false,
            'deliverable_type' => 'other',
            'deliverable_url' => 'https://example.com/tes',
            'platform_type' => 'other',
            'button_text' => 'Beli',
        ]);

        $response = $this->actingAs($this->user)->deleteJson(route('admin.elements.digital-product.destroy', $product->id));
        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertSoftDeleted('digital_products', [
            'id' => $product->id
        ]);
    }
}
