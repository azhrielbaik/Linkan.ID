<?php

namespace Tests\Feature;

use App\Models\Shortlink;
use App\Models\ShortlinkClick;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShortlinkFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_store_accepts_title_and_description()
    {
        $response = $this->post(route('admin.shortlinks.store'), [
            'title' => 'My Test Title',
            'description' => 'My Test Description',
            'slug' => 'test-slug-1',
            'destination' => 'https://example.com/dest1'
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('shortlinks', [
            'slug' => 'test-slug-1',
            'title' => 'My Test Title',
            'description' => 'My Test Description'
        ]);
    }

    public function test_store_stores_null_when_title_omitted()
    {
        $response = $this->post(route('admin.shortlinks.store'), [
            'slug' => 'test-slug-2',
            'destination' => 'https://example.com/dest2'
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('shortlinks', [
            'slug' => 'test-slug-2',
            'title' => null,
            'description' => null
        ]);
    }

    public function test_store_validates_title_max_length()
    {
        $response = $this->post(route('admin.shortlinks.store'), [
            'title' => str_repeat('a', 256),
            'slug' => 'test-slug-3',
            'destination' => 'https://example.com/dest3'
        ]);

        $response->assertSessionHasErrors(['title']);
    }

    public function test_index_renders_card_list()
    {
        Shortlink::create([
            'user_id' => $this->user->id,
            'slug' => 'test-render-slug',
            'destination' => 'https://example.com/render',
            'title' => 'Render Title'
        ]);

        $response = $this->get(route('admin.shortlinks.index'));
        $response->assertOk();
        $response->assertSee('sl-card');
        $response->assertSee('Render Title');
        $response->assertSee('test-render-slug');
        $response->assertSee('https://example.com/render');
        $response->assertSee('sl-btn--copy'); // Assuming the class is sl-btn--copy
    }

    public function test_analytics_chart_includes_ip_breakdown()
    {
        $shortlink = Shortlink::create([
            'user_id' => $this->user->id,
            'slug' => 'test-analytics-1',
            'destination' => 'https://example.com'
        ]);

        ShortlinkClick::create([
            'shortlink_id' => $shortlink->id,
            'user_id' => $this->user->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mobile Safari',
            'source' => 'direct'
        ]);
        ShortlinkClick::create([
            'shortlink_id' => $shortlink->id,
            'user_id' => $this->user->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mobile Safari',
            'source' => 'direct'
        ]);

        $response = $this->get(route('admin.shortlinks.analytics.chart', $shortlink->id));
        $response->assertOk();
        $json = $response->json();
        
        $this->assertArrayHasKey('ip_breakdown', $json);
        $this->assertCount(1, $json['ip_breakdown']);
        $this->assertEquals('127.0.0.1', $json['ip_breakdown'][0]['label']);
        $this->assertEquals(2, $json['ip_breakdown'][0]['total']);
    }

    public function test_analytics_chart_includes_device_breakdown()
    {
        $shortlink = Shortlink::create([
            'user_id' => $this->user->id,
            'slug' => 'test-analytics-2',
            'destination' => 'https://example.com'
        ]);

        ShortlinkClick::create([
            'shortlink_id' => $shortlink->id,
            'user_id' => $this->user->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_6 like Mac OS X)', // Mobile
            'source' => 'direct'
        ]);
        ShortlinkClick::create([
            'shortlink_id' => $shortlink->id,
            'user_id' => $this->user->id,
            'ip_address' => '127.0.0.2',
            'user_agent' => 'Mozilla/5.0 (iPad; CPU OS 14_6 like Mac OS X)', // Tablet
            'source' => 'direct'
        ]);
        ShortlinkClick::create([
            'shortlink_id' => $shortlink->id,
            'user_id' => $this->user->id,
            'ip_address' => '127.0.0.3',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', // Desktop
            'source' => 'direct'
        ]);

        $response = $this->get(route('admin.shortlinks.analytics.chart', $shortlink->id));
        $response->assertOk();
        $json = $response->json();
        
        $this->assertArrayHasKey('device_breakdown', $json);
        $this->assertCount(3, $json['device_breakdown']);
        
        $deviceMap = [];
        foreach ($json['device_breakdown'] as $entry) {
            $deviceMap[$entry['label']] = $entry['total'];
        }

        $this->assertEquals(1, $deviceMap['Mobile']);
        $this->assertEquals(1, $deviceMap['Tablet']);
        $this->assertEquals(1, $deviceMap['Desktop']);
    }

    public function test_analytics_chart_empty_for_no_clicks()
    {
        $shortlink = Shortlink::create([
            'user_id' => $this->user->id,
            'slug' => 'test-analytics-3',
            'destination' => 'https://example.com'
        ]);

        $response = $this->get(route('admin.shortlinks.analytics.chart', $shortlink->id));
        $response->assertOk();
        $json = $response->json();
        
        $this->assertEmpty($json['ip_breakdown']);
        
        // device_breakdown always returns 3 items even if 0
        $this->assertCount(3, $json['device_breakdown']);
        foreach ($json['device_breakdown'] as $entry) {
            $this->assertEquals(0, $entry['total']);
        }
    }

    public function test_analytics_chart_has_required_fields()
    {
        $shortlink = Shortlink::create([
            'user_id' => $this->user->id,
            'slug' => 'test-analytics-4',
            'destination' => 'https://example.com'
        ]);

        $response = $this->get(route('admin.shortlinks.analytics.chart', $shortlink->id));
        $json = $response->json();

        $requiredFields = ['labels', 'clicks', 'sources', 'total_clicks', 'start_date', 'end_date', 'ip_breakdown', 'device_breakdown'];
        
        foreach ($requiredFields as $field) {
            $this->assertArrayHasKey($field, $json);
        }
    }
    
    public function test_card_renders_title_or_slug_fallback()
    {
        $link1 = Shortlink::create([
            'user_id' => $this->user->id,
            'slug' => 'slug-only-1',
            'destination' => 'https://example.com/1',
            'title' => null
        ]);
        
        $link2 = Shortlink::create([
            'user_id' => $this->user->id,
            'slug' => 'slug-only-2',
            'destination' => 'https://example.com/2',
            'title' => 'Explicit Title'
        ]);

        $response = $this->get(route('admin.shortlinks.index'));
        $response->assertOk();
        $response->assertSee('Tanpa Judul (slug-only-1)');
        $response->assertSee('Explicit Title');
    }

    public function test_detail_panel_data_attributes_present()
    {
        $link = Shortlink::create([
            'user_id' => $this->user->id,
            'slug' => 'test-panel-slug',
            'destination' => 'https://example.com/panel',
            'title' => 'Panel Title',
            'description' => 'Panel Description'
        ]);

        $response = $this->get(route('admin.shortlinks.index'));
        $response->assertOk();
        
        $response->assertSee('data-id="' . $link->id . '"', false);
        $response->assertSee('data-title="Panel Title"', false);
        $response->assertSee('data-description="Panel Description"', false);
        $response->assertSee('data-destination="https://example.com/panel"', false);
        $response->assertSee('data-url="' . url('/' . $link->slug) . '"', false);
        $response->assertSee('data-created="' . $link->created_at->format('d M Y, H:i') . '"', false);
        $response->assertSee('data-updated="' . $link->updated_at->format('d M Y, H:i') . '"', false);
    }
}
