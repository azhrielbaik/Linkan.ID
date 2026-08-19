<?php

namespace Tests\Feature;

use App\Models\Shortlink;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShortlinkAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_shortlink_redirect_records_a_click_with_source_details(): void
    {
        $user = User::create([
            'name' => 'Seller One',
            'username' => 'seller-one',
            'email' => 'seller-one@example.com',
            'password' => 'password123',
        ]);

        $shortlink = Shortlink::create([
            'user_id' => $user->id,
            'slug' => 'promo-ig',
            'destination' => 'https://example.com/product',
        ]);

        $response = $this->withHeader('Referer', 'https://instagram.com/seller-post')
            ->get('/promo-ig?utm_source=instagram');

        $response->assertRedirect('https://example.com/product');

        $this->assertDatabaseHas('shortlink_clicks', [
            'shortlink_id' => $shortlink->id,
            'user_id' => $user->id,
            'source' => 'instagram',
            'referer' => 'https://instagram.com/seller-post',
        ]);
    }

    public function test_shortlink_index_only_shows_logged_in_user_click_totals_and_sources(): void
    {
        $owner = User::create([
            'name' => 'Owner',
            'username' => 'owner-user',
            'email' => 'owner@example.com',
            'password' => 'password123',
        ]);

        $otherUser = User::create([
            'name' => 'Other',
            'username' => 'other-user',
            'email' => 'other@example.com',
            'password' => 'password123',
        ]);

        $ownerShortlink = Shortlink::create([
            'user_id' => $owner->id,
            'slug' => 'owner-link',
            'destination' => 'https://example.com/owner',
        ]);

        $otherShortlink = Shortlink::create([
            'user_id' => $otherUser->id,
            'slug' => 'other-link',
            'destination' => 'https://example.com/other',
        ]);

        $this->withHeader('Referer', 'https://instagram.com/story')
            ->get('/owner-link?utm_source=instagram');
        $this->withHeader('Referer', 'https://google.com/search?q=owner')
            ->get('/owner-link');
        $this->get('/other-link');
        $response = $this->actingAs($owner)->get(route('admin.shortlinks.index'));

        $response->assertOk();
        $response->assertSee('owner-link');
        $response->assertSee('2');
        $response->assertDontSee('instagram');
        $response->assertDontSee('google.com');
        $response->assertSee('Analitik');
        $response->assertDontSee('other-link');
    }

    public function test_shortlink_analytics_page_shows_owner_only_metrics_and_chart_data(): void
    {
        $owner = User::create([
            'name' => 'Analytics Owner',
            'username' => 'analytics-owner',
            'email' => 'analytics-owner@example.com',
            'password' => 'password123',
        ]);

        $otherUser = User::create([
            'name' => 'Analytics Other',
            'username' => 'analytics-other',
            'email' => 'analytics-other@example.com',
            'password' => 'password123',
        ]);

        $ownerShortlink = Shortlink::create([
            'user_id' => $owner->id,
            'slug' => 'analytics-link',
            'destination' => 'https://example.com/analytics',
        ]);

        $otherShortlink = Shortlink::create([
            'user_id' => $otherUser->id,
            'slug' => 'hidden-analytics-link',
            'destination' => 'https://example.com/hidden',
        ]);

        $this->withHeader('Referer', 'https://instagram.com/reel')
            ->get('/analytics-link?utm_source=instagram');
        $this->withHeader('Referer', 'https://google.com/search?q=analytics')
            ->get('/analytics-link');
        $this->get('/hidden-analytics-link');

        $page = $this->actingAs($owner)->get(route('admin.shortlinks.analytics', $ownerShortlink));

        $page->assertOk();
        $page->assertSee(__('admin.analytics_shortlink'));
        $page->assertSee('analytics-link');
        $page->assertSee('2');
        $page->assertSee(__('admin.source_traffic_chart'));
        $page->assertSee('instagram');
        $page->assertSee('google.com');
        $page->assertDontSee('hidden-analytics-link');

        $chart = $this->actingAs($owner)->getJson(route('admin.shortlinks.analytics.chart', $ownerShortlink));

        $chart->assertOk();
        $chart->assertJsonPath('total_clicks', 2);
        $chart->assertJsonCount(7, 'labels');
        $chart->assertJsonCount(7, 'clicks');
        $chart->assertJsonPath('sources.0.label', 'instagram');
        $chart->assertJsonPath('sources.0.total', 1);
    }
}
