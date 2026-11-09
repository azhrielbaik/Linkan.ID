<?php

namespace Tests\Feature;

use App\Helpers\ReviewTokenHelper;
use App\Models\Appearance;
use App\Models\DigitalProduct;
use App\Models\Shortlink;
use App\Models\Transaction;
use App\Models\User;
use App\Services\CheckoutService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    protected User $seller;
    protected Appearance $appearance;
    protected DigitalProduct $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seller = User::factory()->create([
            'username' => 'seoseller',
            'email' => 'seoseller@example.com',
        ]);

        $this->appearance = Appearance::create([
            'user_id' => $this->seller->id,
            'alias' => 'seoseller',
            'name' => 'SEO Seller Studio',
            'bio' => 'Menyediakan aneka produk digital, e-book, dan template kreatif terbaik.',
            'theme_color' => '#3b82f6',
        ]);

        $this->product = DigitalProduct::create([
            'user_id' => $this->seller->id,
            'title' => 'Panduan SEO Teknis Laravel',
            'description' => 'Buku digital panduan lengkap optimasi on-page dan teknis SEO untuk aplikasi Laravel.',
            'price' => 75000,
            'pricing_type' => 'fixed',
            'button_text' => 'Beli Sekarang',
            'is_active' => true,
            'deliverable_type' => 'other',
            'deliverable_url' => 'https://example.com/download-seo-guide',
        ]);
    }

    /**
     * Helper to extract tags using regex from HTML string
     */
    protected function extractTag(string $html, string $pattern): array
    {
        preg_match_all($pattern, $html, $matches);
        return $matches[0] ?? [];
    }

    /**
     * Verify standard public pages render required SEO metadata in both locales.
     */
    public function test_public_pages_render_correct_seo_meta_tags(): void
    {
        $locales = ['id', 'en'];
        $routes = [
            'welcome' => fn ($locale) => url('/' . $locale),
            'about' => fn ($locale) => route('about', ['locale' => $locale]),
            'faq' => fn ($locale) => route('FAQ', ['locale' => $locale]),
            'contact' => fn ($locale) => route('contact.form', ['locale' => $locale]),
        ];

        foreach ($locales as $locale) {
            foreach ($routes as $name => $urlResolver) {
                $url = $urlResolver($locale);
                $response = $this->get($url);

                $response->assertStatus(200);
                $html = $response->getContent();

                // 1. Tepat 1 tag <title> dengan panjang <= 60 karakter
                preg_match('/<title>(.*?)<\/title>/is', $html, $titleMatches);
                $this->assertNotEmpty($titleMatches, "Missing <title> tag on {$url}");
                $titleText = trim(strip_tags($titleMatches[1]));
                $this->assertLessThanOrEqual(60, mb_strlen($titleText), "Title exceeds 60 chars on {$url}: '{$titleText}'");

                // 2. Tepat 1 meta description
                $descMatches = $this->extractTag($html, '/<meta\s+name=["\']description["\']\s+content=["\']([^"\']*)["\']/i');
                $this->assertCount(1, $descMatches, "Expected exactly 1 meta description on {$url}");

                // 3. Self-referencing absolute canonical tag
                $canonicalMatches = $this->extractTag($html, '/<link\s+rel=["\']canonical["\']\s+href=["\']([^"\']*)["\']/i');
                $this->assertCount(1, $canonicalMatches, "Expected exactly 1 canonical tag on {$url}");
                $this->assertStringContainsString($url, $canonicalMatches[0], "Canonical does not match active URL on {$url}");

                // 4. Reciprocal hreflang tags (id, en, x-default)
                $this->assertStringContainsString('hreflang="id"', $html, "Missing hreflang='id' on {$url}");
                $this->assertStringContainsString('hreflang="en"', $html, "Missing hreflang='en' on {$url}");
                $this->assertStringContainsString('hreflang="x-default"', $html, "Missing hreflang='x-default' on {$url}");

                // 5. OpenGraph core tags
                $this->assertStringContainsString('property="og:title"', $html, "Missing og:title on {$url}");
                $this->assertStringContainsString('property="og:description"', $html, "Missing og:description on {$url}");
                $this->assertStringContainsString('property="og:url"', $html, "Missing og:url on {$url}");
                $this->assertStringContainsString('property="og:image"', $html, "Missing og:image on {$url}");
                $this->assertStringContainsString('property="og:site_name"', $html, "Missing og:site_name on {$url}");

                // 6. Twitter card tags
                $this->assertStringContainsString('name="twitter:card"', $html, "Missing twitter:card on {$url}");
                $this->assertStringContainsString('name="twitter:title"', $html, "Missing twitter:title on {$url}");
                $this->assertStringContainsString('name="twitter:image"', $html, "Missing twitter:image on {$url}");

                // 7. Tepat 1 <h1> tag
                $h1Matches = $this->extractTag($html, '/<h1[^>]*>.*?<\/h1>/is');
                $this->assertCount(1, $h1Matches, "Expected exactly 1 <h1> tag on {$url}, found " . count($h1Matches));
            }
        }
    }

    /**
     * Verify JSON-LD on Welcome and FAQ pages parses cleanly and contains required schema types.
     */
    public function test_welcome_and_faq_pages_contain_valid_json_ld(): void
    {
        // 1. Welcome page (Organization + WebSite)
        $welcomeResponse = $this->get(url('/id'));
        $welcomeResponse->assertStatus(200);
        $welcomeHtml = $welcomeResponse->getContent();

        preg_match_all('/<script\s+type=["\']application\/ld\+json["\']>(.*?)<\/script>/is', $welcomeHtml, $scriptMatches);
        $this->assertNotEmpty($scriptMatches[1], 'No JSON-LD scripts found on Welcome page');

        $foundWebsite = false;
        $foundOrg = false;
        foreach ($scriptMatches[1] as $rawJson) {
            $data = json_decode(trim($rawJson), true);
            $this->assertSame(JSON_ERROR_NONE, json_last_error(), 'Welcome JSON-LD failed to parse: ' . $rawJson);
            if (isset($data['@type'])) {
                if ($data['@type'] === 'WebSite') $foundWebsite = true;
                if ($data['@type'] === 'Organization') $foundOrg = true;
            }
        }
        $this->assertTrue($foundWebsite, 'Welcome page missing WebSite schema in JSON-LD');
        $this->assertTrue($foundOrg, 'Welcome page missing Organization schema in JSON-LD');

        // 2. FAQ page (FAQPage)
        $faqResponse = $this->get(route('FAQ', ['locale' => 'id']));
        $faqResponse->assertStatus(200);
        $faqHtml = $faqResponse->getContent();

        preg_match_all('/<script\s+type=["\']application\/ld\+json["\']>(.*?)<\/script>/is', $faqHtml, $faqScripts);
        $this->assertNotEmpty($faqScripts[1], 'No JSON-LD scripts found on FAQ page');

        $foundFaq = false;
        foreach ($faqScripts[1] as $rawJson) {
            $data = json_decode(trim($rawJson), true);
            $this->assertSame(JSON_ERROR_NONE, json_last_error(), 'FAQ JSON-LD failed to parse');
            if (isset($data['@type']) && $data['@type'] === 'FAQPage') {
                $foundFaq = true;
                $this->assertArrayHasKey('mainEntity', $data);
                $this->assertNotEmpty($data['mainEntity']);
            }
        }
        $this->assertTrue($foundFaq, 'FAQ page missing FAQPage schema in JSON-LD');
    }

    /**
     * Verify seller microsite renders dynamic profile SEO tags and valid ProfilePage/Person JSON-LD.
     */
    public function test_microsite_profile_renders_complete_seo_meta_and_json_ld(): void
    {
        $url = route('track.view', ['locale' => 'id', 'username' => $this->appearance->alias]);
        $response = $this->get($url);

        $response->assertStatus(200);
        $html = $response->getContent();

        // 1. Title contains appearance name
        $this->assertStringContainsString('SEO Seller Studio', $html);
        preg_match('/<title>(.*?)<\/title>/is', $html, $titleMatches);
        $titleText = trim(strip_tags($titleMatches[1] ?? ''));
        $this->assertLessThanOrEqual(60, mb_strlen($titleText));

        // 2. Canonical points to microsite URL
        $this->assertStringContainsString('href="' . $url . '"', $html);

        // 3. Reciprocal hreflang
        $this->assertStringContainsString('hreflang="id"', $html);
        $this->assertStringContainsString('hreflang="en"', $html);

        // 4. JSON-LD ProfilePage & Person
        preg_match_all('/<script\s+type=["\']application\/ld\+json["\']>(.*?)<\/script>/is', $html, $scripts);
        $this->assertNotEmpty($scripts[1], 'No JSON-LD on microsite page');

        $foundProfilePage = false;
        foreach ($scripts[1] as $rawJson) {
            $data = json_decode(trim($rawJson), true);
            $this->assertSame(JSON_ERROR_NONE, json_last_error(), 'Microsite JSON-LD parse error');
            if (isset($data['@type']) && $data['@type'] === 'ProfilePage') {
                $foundProfilePage = true;
                $this->assertEquals('Person', $data['mainEntity']['@type'] ?? null);
                $this->assertEquals($this->appearance->name, $data['mainEntity']['name'] ?? null);
            }
        }
        $this->assertTrue($foundProfilePage, 'Microsite missing ProfilePage schema');

        // 5. Semantic <h1>
        $h1Matches = $this->extractTag($html, '/<h1[^>]*>.*?<\/h1>/is');
        $this->assertCount(1, $h1Matches, 'Expected exactly 1 <h1> tag on microsite');
        $this->assertStringContainsString('SEO Seller Studio', $h1Matches[0]);
    }

    /**
     * Verify digital product detail page renders dynamic product SEO tags and valid Product/Offer JSON-LD.
     */
    public function test_digital_product_detail_renders_complete_seo_meta_and_json_ld(): void
    {
        $url = route('product.show', ['locale' => 'id', 'id' => $this->product->id]);
        $response = $this->get($url);

        $response->assertStatus(200);
        $html = $response->getContent();

        // 1. Title contains product title and length <= 60 chars
        $this->assertStringContainsString('Panduan SEO Teknis Laravel', $html);
        preg_match('/<title>(.*?)<\/title>/is', $html, $titleMatches);
        $titleText = trim(strip_tags($titleMatches[1] ?? ''));
        $this->assertLessThanOrEqual(60, mb_strlen($titleText));

        // 2. Canonical is self-referencing absolute
        $this->assertStringContainsString('href="' . $url . '"', $html);

        // 3. JSON-LD Product & Offer
        preg_match_all('/<script\s+type=["\']application\/ld\+json["\']>(.*?)<\/script>/is', $html, $scripts);
        $this->assertNotEmpty($scripts[1], 'No JSON-LD on product detail page');

        $foundProduct = false;
        foreach ($scripts[1] as $rawJson) {
            $data = json_decode(trim($rawJson), true);
            $this->assertSame(JSON_ERROR_NONE, json_last_error(), 'Product JSON-LD parse error');
            if (isset($data['@type']) && $data['@type'] === 'Product') {
                $foundProduct = true;
                $this->assertEquals('Panduan SEO Teknis Laravel', $data['name']);
                $this->assertEquals('Offer', $data['offers']['@type'] ?? null);
                $this->assertEquals(75000, $data['offers']['price'] ?? null);
                $this->assertEquals('IDR', $data['offers']['priceCurrency'] ?? null);
            }
        }
        $this->assertTrue($foundProduct, 'Product detail missing Product schema');

        // 4. Semantic <h1>
        $h1Matches = $this->extractTag($html, '/<h1[^>]*>.*?<\/h1>/is');
        $this->assertCount(1, $h1Matches, 'Expected exactly 1 <h1> tag on product detail');
        $this->assertStringContainsString('Panduan SEO Teknis Laravel', $h1Matches[0]);
    }

    /**
     * Verify transactional and private routes strictly output <meta name="robots" content="noindex, nofollow">.
     */
    public function test_transactional_and_private_pages_send_noindex_nofollow_meta(): void
    {
        // 1. Layanan Pengaduan Sengketa
        $disputeUrl = route('public.dispute.create', ['locale' => 'id']);
        $disputeRes = $this->get($disputeUrl);
        $disputeRes->assertStatus(200);
        $disputeRes->assertSee('<meta name="robots" content="noindex, nofollow">', false);

        // 2. Checkout Success receipt page
        $successUrl = route('checkout.success', ['locale' => 'id', 'id' => $this->product->id]);
        $successRes = $this->get($successUrl);
        $successRes->assertStatus(200);
        $successRes->assertSee('<meta name="robots" content="noindex, nofollow">', false);

        // 3. Checkout Form page (Mock CheckoutService)
        $this->mock(CheckoutService::class, function ($mock) {
            $mock->shouldReceive('generateSnapToken')->andReturn([
                'totalPrice' => 75000,
                'itemPrice' => 75000,
                'snapToken' => 'mock-snap-token-xyz',
                'orderId' => 'ORDER-SEO-TEST-01',
            ]);
        });
        $checkoutUrl = route('checkout', ['locale' => 'id', 'id' => $this->product->id]);
        $checkoutRes = $this->get($checkoutUrl);
        $checkoutRes->assertStatus(200);
        $checkoutRes->assertSee('<meta name="robots" content="noindex, nofollow">', false);

        // 4. Shortlink password challenge page
        $shortlink = Shortlink::create([
            'user_id' => $this->seller->id,
            'title' => 'Protected Link',
            'slug' => 'protected-secret',
            'destination' => 'https://example.com/target',
            'password' => bcrypt('secret123'),
        ]);
        $pwdUrl = route('shortlink.password.form', ['locale' => 'id', 'slug' => $shortlink->slug]);
        $pwdRes = $this->get($pwdUrl);
        $pwdRes->assertStatus(200);
        $pwdRes->assertSee('<meta name="robots" content="noindex, nofollow">', false);

        // 5. Product Review page
        $transaction = Transaction::create([
            'product_id' => $this->product->id,
            'order_id' => 'ORDER-REV-SEO-99',
            'buyer_name' => 'Buyer Reviewer',
            'buyer_email' => 'buyer@example.com',
            'qty' => 1,
            'total_price' => 75000,
            'status' => 'success',
        ]);
        $token = ReviewTokenHelper::generate($transaction->order_id);
        $reviewUrl = route('public.review.show', ['locale' => 'id', 'token' => $token]);
        $reviewRes = $this->get($reviewUrl);
        $reviewRes->assertStatus(200);
        $reviewRes->assertSee('<meta name="robots" content="noindex, nofollow">', false);
    }

    /**
     * Verify sitemap generator produces 200-only URLs with hreflang alternate links and dynamic models.
     */
    public function test_sitemap_command_generates_valid_xml_with_200_urls_and_hreflang(): void
    {
        $exitCode = Artisan::call('sitemap:generate');
        $this->assertSame(0, $exitCode, 'Artisan sitemap:generate command failed');

        $sitemapPath = public_path('sitemap.xml');
        $this->assertFileExists($sitemapPath, 'sitemap.xml was not created');

        $content = File::get($sitemapPath);

        // Assert valid XML
        $xml = simplexml_load_string($content);
        $this->assertNotFalse($xml, 'sitemap.xml is not valid XML');

        // Assert no redirect URLs
        $this->assertStringNotContainsString('<loc>' . url('/') . '</loc>', $content, 'Sitemap contains redirect root URL');
        $this->assertStringNotContainsString('/pricing</loc>', $content, 'Sitemap contains redirect pricing URL');

        // Assert localized static URLs are present
        $this->assertStringContainsString('<loc>' . url('/id') . '</loc>', $content);
        $this->assertStringContainsString('<loc>' . url('/en') . '</loc>', $content);
        $this->assertStringContainsString('<loc>' . url('/id/about') . '</loc>', $content);
        $this->assertStringContainsString('<loc>' . url('/id/faq') . '</loc>', $content);
        $this->assertStringContainsString('<loc>' . url('/id/contact') . '</loc>', $content);

        // Assert hreflang alternates are embedded
        $this->assertStringContainsString('hreflang="id"', $content);
        $this->assertStringContainsString('hreflang="en"', $content);
        $this->assertStringContainsString('hreflang="x-default"', $content);

        // Assert dynamic seller microsite and product URLs are present
        $this->assertStringContainsString(url('/id/linkan.id/' . $this->appearance->alias), $content);
        $this->assertStringContainsString(url('/id/product/' . $this->product->id), $content);
    }

    /**
     * Verify public/robots.txt disallows sensitive and transactional paths.
     */
    public function test_robots_txt_protects_private_routes(): void
    {
        $robotsPath = public_path('robots.txt');
        $this->assertFileExists($robotsPath);

        $content = File::get($robotsPath);

        $this->assertStringContainsString('Disallow: /admin/', $content);
        $this->assertStringContainsString('Disallow: /platform-admin/', $content);
        $this->assertStringContainsString('Disallow: /checkout/', $content);
        $this->assertStringContainsString('Disallow: /review/', $content);
        $this->assertStringContainsString('Disallow: /bantuan/', $content);
        $this->assertStringContainsString('Disallow: /p/', $content);
        $this->assertStringContainsString('Sitemap:', $content);
        $this->assertStringContainsString('sitemap.xml', $content);
    }
}
