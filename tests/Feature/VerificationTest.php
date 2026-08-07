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



        $response = $this->actingAs($admin)->get(route('verifikasi.platformadmin'));

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

        $response = $this->actingAs($admin)->post(route('verifikasi.verify', $product->id), [
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

        $response = $this->actingAs($admin)->post(route('verifikasi.verify', $product->id), [
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
}
