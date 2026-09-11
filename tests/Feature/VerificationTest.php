<?php

namespace Tests\Feature;

use App\Models\DigitalProduct;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test verification index loads successfully, even if a product has no associated user.
     */
    public function test_verification_index_loads_successfully_with_or_without_users(): void
    {
        $admin = User::factory()->create(['role' => 'admin_platform']);

        // Create a product with a valid user
        $seller = User::factory()->create();
        DigitalProduct::create([
            'user_id' => $seller->id,
            'title' => 'Test Product 1',
            'description' => 'A valid product',
            'price' => 10000,
            'status' => 'active',
            'platform_type' => 'upload',
            'button_text' => 'Beli Sekarang',
            'is_featured' => 0,
            'verification_status' => 'pending'
        ]);



        $response = $this->actingAs($admin)->get(route('platform-admin.verifikasi'));

        $response->assertStatus(200);
        $response->assertSee('A valid product');
    }

    /**
     * Test product verification status update.
     */
    public function test_can_update_product_verification_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin_platform']);
        $seller = User::factory()->create();
        
        $product = DigitalProduct::create([
            'user_id' => $seller->id,
            'title' => 'Test Product',
            'description' => 'Description',
            'price' => 10000,
            'status' => 'active',
            'platform_type' => 'upload',
            'button_text' => 'Beli Sekarang',
            'is_featured' => 0,
            'verification_status' => 'pending'
        ]);

        $response = $this->actingAs($admin)->post(route('platform-admin.verifikasi.verify', $product->id), [
            'status' => 'approved'
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Status verifikasi produk berhasil diperbarui');

        $this->assertDatabaseHas('digital_products', [
            'id' => $product->id,
            'verification_status' => 'approved',
            'rejection_reason' => null
        ]);
    }

    /**
     * Test product verification rejection with reason.
     */
    public function test_can_reject_product_with_reason(): void
    {
        $admin = User::factory()->create(['role' => 'admin_platform']);
        $seller = User::factory()->create();
        
        $product = DigitalProduct::create([
            'user_id' => $seller->id,
            'title' => 'Test Product',
            'description' => 'Description',
            'price' => 10000,
            'status' => 'active',
            'platform_type' => 'upload',
            'button_text' => 'Beli Sekarang',
            'is_featured' => 0,
            'verification_status' => 'pending'
        ]);

        $response = $this->actingAs($admin)->post(route('platform-admin.verifikasi.verify', $product->id), [
            'status' => 'rejected',
            'rejection_reason' => 'Data tidak lengkap'
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Status verifikasi produk berhasil diperbarui');

        $this->assertDatabaseHas('digital_products', [
            'id' => $product->id,
            'verification_status' => 'rejected',
            'rejection_reason' => 'Data tidak lengkap'
        ]);
    }

    /**
     * Test verification filtering by status tab.
     */
    public function test_verification_filters_by_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin_platform']);
        $seller = User::factory()->create();

        DigitalProduct::create([
            'user_id' => $seller->id,
            'title' => 'Pending Item',
            'description' => 'Pending Description',
            'price' => 10000,
            'status' => 'active',
            'platform_type' => 'upload',
            'button_text' => 'Beli',
            'is_featured' => 0,
            'verification_status' => 'pending'
        ]);

        DigitalProduct::create([
            'user_id' => $seller->id,
            'title' => 'Approved Item',
            'description' => 'Approved Description',
            'price' => 20000,
            'status' => 'active',
            'platform_type' => 'upload',
            'button_text' => 'Beli',
            'is_featured' => 0,
            'verification_status' => 'approved'
        ]);

        // When viewing pending tab (default)
        $response = $this->actingAs($admin)->get(route('platform-admin.verifikasi', ['status' => 'pending']));
        $response->assertStatus(200);
        $response->assertSee('Pending Item');
        $response->assertDontSee('Approved Item');

        // When viewing approved tab
        $response = $this->actingAs($admin)->get(route('platform-admin.verifikasi', ['status' => 'approved']));
        $response->assertStatus(200);
        $response->assertSee('Approved Item');
        $response->assertDontSee('Pending Item');
    }

    /**
     * Test verification search filter.
     */
    public function test_verification_filters_by_search_query(): void
    {
        $admin = User::factory()->create(['role' => 'admin_platform']);
        $seller = User::factory()->create(['name' => 'John Doe Seller']);

        DigitalProduct::create([
            'user_id' => $seller->id,
            'title' => 'Super Unique Product Title',
            'description' => 'Desc',
            'price' => 10000,
            'status' => 'active',
            'platform_type' => 'upload',
            'button_text' => 'Beli',
            'is_featured' => 0,
            'verification_status' => 'pending'
        ]);

        DigitalProduct::create([
            'user_id' => $seller->id,
            'title' => 'Regular Item',
            'description' => 'Desc',
            'price' => 10000,
            'status' => 'active',
            'platform_type' => 'upload',
            'button_text' => 'Beli',
            'is_featured' => 0,
            'verification_status' => 'pending'
        ]);

        $response = $this->actingAs($admin)->get(route('platform-admin.verifikasi', ['search' => 'Super Unique']));
        $response->assertStatus(200);
        $response->assertSee('Super Unique Product Title');
        $response->assertDontSee('Regular Item');
    }

    /**
     * Test verification paginates products to 15 items per page.
     */
    public function test_verification_paginates_products(): void
    {
        $admin = User::factory()->create(['role' => 'admin_platform']);
        $seller = User::factory()->create();

        for ($i = 1; $i <= 20; $i++) {
            DigitalProduct::create([
                'user_id' => $seller->id,
                'title' => "Batch Product Item {$i}",
                'description' => 'Desc',
                'price' => 10000,
                'status' => 'active',
                'platform_type' => 'upload',
                'button_text' => 'Beli',
                'is_featured' => 0,
                'verification_status' => 'pending',
                'created_at' => now()->subMinutes(25 - $i)
            ]);
        }

        $response = $this->actingAs($admin)->get(route('platform-admin.verifikasi', ['page' => 1]));
        $response->assertStatus(200);
        $productsInView = $response->viewData('products');
        $this->assertEquals(15, $productsInView->count());
        $this->assertEquals(20, $productsInView->total());

        $responsePage2 = $this->actingAs($admin)->get(route('platform-admin.verifikasi', ['page' => 2]));
        $responsePage2->assertStatus(200);
        $productsInViewPage2 = $responsePage2->viewData('products');
        $this->assertEquals(5, $productsInViewPage2->count());
    }
}
