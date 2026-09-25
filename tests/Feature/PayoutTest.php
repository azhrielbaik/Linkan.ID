<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PayoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test unauthenticated user is redirected to login.
     */
    public function test_unauthenticated_user_cannot_access_payout(): void
    {
        $response = $this->get(route('admin.payout.index'));

        $response->assertRedirect();
    }

    /**
     * Test authenticated seller can access payout overview without 500 error.
     */
    public function test_authenticated_user_can_access_payout_index(): void
    {
        $user = User::factory()->create([
            'role' => 'admin_seller',
            'balance' => 500000,
        ]);

        $response = $this->actingAs($user)->get(route('admin.payout.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin_seller.features.payouts.index');
        $response->assertViewHas(['totalEarnings', 'totalWithdrawn', 'currentBalance', 'frozenDisputeAmount']);
    }

    /**
     * Test authenticated seller can access withdraw form.
     */
    public function test_authenticated_user_can_access_withdraw_form(): void
    {
        $user = User::factory()->create([
            'role' => 'admin_seller',
            'balance' => 500000,
        ]);

        $response = $this->actingAs($user)->get(route('admin.payout.withdraw'));

        $response->assertStatus(200);
        $response->assertViewIs('admin_seller.features.payouts.withdraw');
    }

    /**
     * Test authenticated seller can access payout method form.
     */
    public function test_authenticated_user_can_access_payout_method_form(): void
    {
        $user = User::factory()->create([
            'role' => 'admin_seller',
        ]);

        $response = $this->actingAs($user)->get(route('admin.payout.method'));

        $response->assertStatus(200);
        $response->assertViewIs('admin_seller.features.payouts.method');
    }

    /**
     * Test authenticated seller can access payout history.
     */
    public function test_authenticated_user_can_access_payout_history(): void
    {
        $user = User::factory()->create([
            'role' => 'admin_seller',
        ]);

        $response = $this->actingAs($user)->get(route('admin.payout.history'));

        $response->assertStatus(200);
        $response->assertViewIs('admin_seller.features.payouts.history');
    }
}
