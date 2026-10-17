<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_access_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin_seller.features.dashboard.index');
        $response->assertSee(__('admin.dashboard_title'));
        $response->assertSee('chart-wrapper');
        $response->assertSee('statsChart');
    }

    public function test_chart_data_endpoint_returns_json_series(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson(route('admin.chart-data', ['days' => 60]));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'labels',
            'views',
            'clicks',
            'start_date',
            'end_date',
            'days'
        ]);
        $this->assertEquals(60, $response->json('days'));
    }
}
