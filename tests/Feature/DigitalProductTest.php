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
        \Illuminate\Support\Facades\Event::fake();
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

    public function test_destroy_digital_product_only_unpins_from_microsite_and_preserves_product(): void
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

        $appearanceA = \App\Models\Appearance::create([
            'user_id' => $this->user->id,
            'name' => 'Microsite A',
            'alias' => 'microsite-a',
            'blocks_order' => 'profile,digitalproduct_' . $product->id . ',text_1'
        ]);

        $appearanceB = \App\Models\Appearance::create([
            'user_id' => $this->user->id,
            'name' => 'Microsite B',
            'alias' => 'microsite-b',
            'blocks_order' => 'profile,digitalproduct_' . $product->id . ',video_1'
        ]);

        $response = $this->actingAs($this->user)->deleteJson(
            route('admin.elements.digital-product.destroy', $product->id),
            ['appearance_id' => $appearanceA->id]
        );

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        // Produk TIDAK boleh terhapus dari database (harus tetap ada di Toko)
        $this->assertDatabaseHas('digital_products', [
            'id' => $product->id,
            'deleted_at' => null
        ]);

        // Appearance A tidak lagi memiliki produk ini
        $appearanceA->refresh();
        $this->assertStringNotContainsString('digitalproduct_' . $product->id, (string) $appearanceA->blocks_order);

        // Appearance B TETAP memiliki produk ini
        $appearanceB->refresh();
        $this->assertStringContainsString('digitalproduct_' . $product->id, (string) $appearanceB->blocks_order);
    }

    public function test_store_digital_product_with_image_upload_success(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $imageFile = \Illuminate\Http\UploadedFile::fake()->image('sample_product.jpg', 600, 600);

        $response = $this->actingAs($this->user)->post(route('admin.elements.digital-product.store'), [
            'title' => 'Produk Dengan Gambar',
            'description' => 'Deskripsi produk gambar',
            'pricing_type' => 'fixed',
            'price_fixed' => 50000,
            'media_count' => 1,
            'media_0' => $imageFile,
            'deliverable_type' => 'other',
            'deliverable_url' => 'https://example.com/item'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'title' => 'Produk Dengan Gambar'
                 ]);

        $product = DigitalProduct::where('title', 'Produk Dengan Gambar')->first();
        $this->assertNotNull($product);
        $this->assertNotNull($product->image);
        $this->assertNotEmpty($product->media_files);

        // Verify the webp file was generated and saved
        $storedPath = $product->media_files[0]['path'];
        $this->assertTrue(\Illuminate\Support\Str::endsWith($storedPath, '.webp'));
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($storedPath);
    }

    public function test_edit_existing_digital_product_and_add_image_success(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $product = DigitalProduct::create([
            'user_id' => $this->user->id,
            'title' => 'Produk Awal',
            'description' => 'Deskripsi lama',
            'pricing_type' => 'fixed',
            'price' => 25000,
            'platform_type' => 'other',
            'button_text' => 'Beli Sekarang',
            'deliverable_type' => 'other',
            'deliverable_url' => 'https://example.com/test'
        ]);

        $newImageFile = \Illuminate\Http\UploadedFile::fake()->image('new_cover.png', 500, 500);

        $response = $this->actingAs($this->user)->post(route('admin.elements.digital-product.store'), [
            'element_id' => $product->id,
            'title' => 'Produk Diedit',
            'description' => 'Deskripsi baru',
            'pricing_type' => 'fixed',
            'price_fixed' => 30000,
            'media_count' => 1,
            'media_0' => $newImageFile,
            'deliverable_type' => 'other',
            'deliverable_url' => 'https://example.com/test'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'id' => $product->id,
                     'title' => 'Produk Diedit'
                 ]);

        $product->refresh();
        $this->assertEquals('Produk Diedit', $product->title);
        $this->assertNotNull($product->image);
        $this->assertCount(1, $product->media_files);

        $storedPath = $product->media_files[0]['path'];
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($storedPath);
    }

    public function test_edit_existing_digital_product_retain_old_media_and_add_new_image_success(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        // Fake existing image in storage
        $oldImagePath = 'digital_products/media/old_product.webp';
        \Illuminate\Support\Facades\Storage::disk('public')->put($oldImagePath, 'fake image content');

        $product = DigitalProduct::create([
            'user_id' => $this->user->id,
            'title' => 'Produk Dua Gambar',
            'description' => 'Deskripsi',
            'pricing_type' => 'fixed',
            'price' => 50000,
            'platform_type' => 'other',
            'button_text' => 'Beli Sekarang',
            'deliverable_type' => 'other',
            'deliverable_url' => 'https://example.com/test',
            'image' => $oldImagePath,
            'media_files' => [
                ['url' => $oldImagePath, 'type' => 'image/webp', 'path' => $oldImagePath]
            ]
        ]);

        $newImageFile = \Illuminate\Http\UploadedFile::fake()->image('second_image.jpg', 600, 600);

        $response = $this->actingAs($this->user)->post(route('admin.elements.digital-product.store'), [
            'element_id' => $product->id,
            'title' => 'Produk Dua Gambar Updated',
            'description' => 'Deskripsi',
            'pricing_type' => 'fixed',
            'price_fixed' => 50000,
            'existing_media' => json_encode([
                ['url' => '/storage/' . $oldImagePath] // simulating URL sent by JS preview
            ]),
            'media_count' => 1,
            'media_0' => $newImageFile,
            'deliverable_type' => 'other',
            'deliverable_url' => 'https://example.com/test'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'id' => $product->id,
                     'title' => 'Produk Dua Gambar Updated'
                 ]);

        $product->refresh();
        $this->assertCount(2, $product->media_files);
        // Pastikan file lama tetap ada di disk
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($oldImagePath);
        // Pastikan file baru juga tersimpan di disk
        $newStoredPath = $product->media_files[1]['path'];
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($newStoredPath);
    }
}


