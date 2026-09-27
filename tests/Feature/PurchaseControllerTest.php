<?php

namespace Tests\Feature;

use App\Models\DigitalProduct;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_purchases_page(): void
    {
        $response = $this->get(route('admin.purchases'));
        $response->assertRedirect();
    }

    public function test_authenticated_user_can_view_purchases(): void
    {
        $user = User::factory()->create([
            'email' => 'buyer@example.com',
        ]);

        $seller = User::factory()->create();

        $product = DigitalProduct::create([
            'user_id' => $seller->id,
            'title' => 'Ebook Laravel Clean Code',
            'description' => 'Panduan arsitektur Laravel',
            'price' => 50000,
            'status' => 'active',
            'platform_type' => 'upload',
            'button_text' => 'Beli Sekarang',
            'is_featured' => 0,
            'verification_status' => 'approved',
        ]);

        Transaction::create([
            'order_id' => 'ORD-TEST-001',
            'product_id' => $product->id,
            'buyer_name' => $user->name,
            'buyer_email' => $user->email,
            'qty' => 1,
            'total_price' => 50000,
            'status' => 'success',
            'payment_method' => 'qris',
        ]);

        $response = $this->actingAs($user)->get(route('admin.purchases'));

        $response->assertOk();
        $response->assertViewIs('admin_seller.features.purchases.index');
        $response->assertSee('Ebook Laravel Clean Code');
    }
}
