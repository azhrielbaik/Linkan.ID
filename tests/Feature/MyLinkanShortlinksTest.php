<?php

namespace Tests\Feature;

use App\Models\Shortlink;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyLinkanShortlinksTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['username' => 'testuser']);
    }

    public function test_mylinkan_page_displays_shortlinks_with_six_items_pagination()
    {
        $this->actingAs($this->user);

        // Create 7 shortlinks for the user with distinct timestamps
        for ($i = 1; $i <= 7; $i++) {
            $link = new Shortlink([
                'user_id' => $this->user->id,
                'slug' => "slug-{$i}",
                'destination' => "https://example.com/dest-{$i}",
                'title' => "Shortlink Title {$i}"
            ]);
            $link->created_at = now()->subDays(10 - $i);
            $link->save();
        }

        $response = $this->get(route('admin.mylinkan', ['mode' => 'edit']));
        $response->assertOk();

        // Should see the section header
        $response->assertSee('Tautan Pendek (Shortlinks)');

        // First page should show the latest 6 shortlinks: slug-7, slug-6, slug-5, slug-4, slug-3, slug-2
        for ($i = 2; $i <= 7; $i++) {
            $response->assertSee("Shortlink Title {$i}");
        }
        // It shouldn't see Shortlink Title 1 on page 1 (since pagination is 6 items per page)
        $response->assertDontSee("Shortlink Title 1");

        // Request page 2
        $responsePage2 = $this->get(route('admin.mylinkan', ['mode' => 'edit', 'links_page' => 2]));
        $responsePage2->assertOk();
        $responsePage2->assertSee("Shortlink Title 1");
        for ($i = 2; $i <= 7; $i++) {
            $responsePage2->assertDontSee("Shortlink Title {$i}");
        }
    }

    public function test_public_profile_page_displays_all_user_shortlinks()
    {
        // Create a shortlink for the user
        Shortlink::create([
            'user_id' => $this->user->id,
            'slug' => 'awesome-link',
            'destination' => 'https://example.com/awesome',
            'title' => 'Awesome Title'
        ]);

        $response = $this->get('/linkan.id/testuser');
        $response->assertOk();
        $response->assertSee('Awesome Title');
        $response->assertSee('/awesome-link');
    }

    public function test_shortlink_index_page_pagination_is_six_items_per_page()
    {
        $this->actingAs($this->user);

        // Create 7 shortlinks for the user with distinct timestamps
        for ($i = 1; $i <= 7; $i++) {
            $link = new Shortlink([
                'user_id' => $this->user->id,
                'slug' => "slug-{$i}",
                'destination' => "https://example.com/dest-{$i}",
                'title' => "Shortlink Title {$i}"
            ]);
            $link->created_at = now()->subDays(10 - $i);
            $link->save();
        }

        $response = $this->get(route('admin.shortlinks.index'));
        $response->assertOk();

        // First page should show the latest 6 shortlinks
        for ($i = 2; $i <= 7; $i++) {
            $response->assertSee("Shortlink Title {$i}");
        }
        $response->assertDontSee("Shortlink Title 1");

        // Request page 2
        $responsePage2 = $this->get(route('admin.shortlinks.index') . '?page=2');
        $responsePage2->assertOk();
        $responsePage2->assertSee("Shortlink Title 1");
        for ($i = 2; $i <= 7; $i++) {
            $responsePage2->assertDontSee("Shortlink Title {$i}");
        }
    }

    public function test_shortlink_index_can_search_by_title_slug_and_destination()
    {
        $this->actingAs($this->user);

        Shortlink::create([
            'user_id' => $this->user->id,
            'slug' => 'slug-toko',
            'destination' => 'https://example.com/dest-toko',
            'title' => 'Promo Toko Baru'
        ]);

        Shortlink::create([
            'user_id' => $this->user->id,
            'slug' => 'slug-diskon',
            'destination' => 'https://example.com/dest-diskon',
            'title' => 'Diskon Akhir Tahun'
        ]);

        // Search for title "Toko"
        $response = $this->get(route('admin.shortlinks.index') . '?search=Toko');
        $response->assertOk();
        $response->assertSee('Promo Toko Baru');
        $response->assertDontSee('Diskon Akhir Tahun');

        // Search for slug "diskon"
        $response2 = $this->get(route('admin.shortlinks.index') . '?search=diskon');
        $response2->assertOk();
        $response2->assertSee('Diskon Akhir Tahun');
        $response2->assertDontSee('Promo Toko Baru');
    }

    public function test_shortlink_index_can_sort_by_popular()
    {
        $this->actingAs($this->user);

        $link1 = Shortlink::create([
            'user_id' => $this->user->id,
            'slug' => 'slug-1',
            'destination' => 'https://example.com/1',
            'title' => 'Less Popular Link'
        ]);

        $link2 = Shortlink::create([
            'user_id' => $this->user->id,
            'slug' => 'slug-2',
            'destination' => 'https://example.com/2',
            'title' => 'More Popular Link'
        ]);

        // Create clicks
        \App\Models\ShortlinkClick::create([
            'shortlink_id' => $link1->id,
            'user_id' => $this->user->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla',
            'source' => 'direct'
        ]);

        for ($i = 0; $i < 3; $i++) {
            \App\Models\ShortlinkClick::create([
                'shortlink_id' => $link2->id,
                'user_id' => $this->user->id,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla',
                'source' => 'direct'
            ]);
        }

        // Sort by popular
        $response = $this->get(route('admin.shortlinks.index') . '?sort=popular');
        $response->assertOk();

        // Verify ordering: More Popular Link should appear before Less Popular Link
        $content = $response->getContent();
        $pos1 = strpos($content, 'More Popular Link');
        $pos2 = strpos($content, 'Less Popular Link');

        $this->assertTrue($pos1 < $pos2, 'More Popular Link should appear before Less Popular Link when sorted by popular');
    }

    public function test_shortlink_index_can_sort_by_oldest_and_newest()
    {
        $this->actingAs($this->user);

        $linkOld = new Shortlink([
            'user_id' => $this->user->id,
            'slug' => 'slug-old',
            'destination' => 'https://example.com/old',
            'title' => 'Oldest Link'
        ]);
        $linkOld->created_at = now()->subDays(10);
        $linkOld->save();

        $linkNew = new Shortlink([
            'user_id' => $this->user->id,
            'slug' => 'slug-new',
            'destination' => 'https://example.com/new',
            'title' => 'Newest Link'
        ]);
        $linkNew->created_at = now()->subDays(1);
        $linkNew->save();

        // Sort by oldest
        $responseOldest = $this->get(route('admin.shortlinks.index') . '?sort=oldest');
        $responseOldest->assertOk();
        $contentOldest = $responseOldest->getContent();
        $posOld1 = strpos($contentOldest, 'Oldest Link');
        $posNew1 = strpos($contentOldest, 'Newest Link');
        $this->assertTrue($posOld1 < $posNew1, 'Oldest Link should appear before Newest Link when sorted by oldest');

        // Sort by newest
        $responseNewest = $this->get(route('admin.shortlinks.index') . '?sort=newest');
        $responseNewest->assertOk();
        $contentNewest = $responseNewest->getContent();
        $posOld2 = strpos($contentNewest, 'Oldest Link');
        $posNew2 = strpos($contentNewest, 'Newest Link');
        $this->assertTrue($posNew2 < $posOld2, 'Newest Link should appear before Oldest Link when sorted by newest');
    }
}
