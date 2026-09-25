<?php

namespace Tests\Feature;

use App\Models\DigitalProduct;
use App\Models\Transaction;
use App\Models\User;
use App\Support\Pagination\EncryptedCursorPaginator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderPaginationSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected User $seller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seller = User::factory()->create([
            'role' => 'admin_seller',
        ]);

        $product = DigitalProduct::withoutEvents(function() {
            return DigitalProduct::create([
                'user_id' => $this->seller->id,
                'title' => 'E-Book Panduan Digital',
                'price' => 50000,
                'description' => 'Test product description',
                'platform_type' => 'other',
                'platform_url' => 'https://example.com/download',
                'button_text' => 'Beli Sekarang',
            ]);
        });

        // Create 25 transactions across different timestamps to ensure multiple pages
        Transaction::withoutEvents(function() use ($product) {
            for ($i = 1; $i <= 25; $i++) {
                Transaction::create([
                    'product_id' => $product->id,
                    'buyer_name' => "Buyer {$i}",
                    'buyer_email' => "buyer{$i}@example.com",
                    'buyer_phone' => '08123456789',
                    'qty' => 1,
                    'total_price' => 50000,
                    'status' => 'success',
                    'order_id' => "ORD-TEST-{$i}",
                    'created_at' => now()->subMinutes(100 - $i),
                    'updated_at' => now()->subMinutes(100 - $i),
                ]);
            }
        });
    }

    /**
     * Test that cursor pagination link does NOT leak internal DB schema (columns/values).
     */
    public function test_cursor_in_pagination_links_is_encrypted_and_does_not_leak_schema(): void
    {
        $response = $this->actingAs($this->seller)->get(route('admin.orders'));

        $response->assertOk();

        // Extract the next page URL
        $content = $response->getContent();
        preg_match('/href="([^"]*cursor=[^"]*)"/', $content, $matches);

        $this->assertNotEmpty($matches, 'Pagination next page link should be present.');

        $nextUrl = html_entity_decode($matches[1]);
        parse_str(parse_url($nextUrl, PHP_URL_QUERY), $queryParams);

        $this->assertArrayHasKey('cursor', $queryParams);
        $cursorToken = $queryParams['cursor'];

        // Ensure raw string does not contain schema keywords
        $this->assertStringNotContainsString('created_at', $cursorToken);
        $this->assertStringNotContainsString('_pointsToNextItems', $cursorToken);

        // Attempting standard base64 decoding MUST NOT reveal internal column names
        $base64Decoded = @base64_decode($cursorToken);
        $this->assertStringNotContainsString('created_at', (string) $base64Decoded);
        $this->assertStringNotContainsString('_pointsToNextItems', (string) $base64Decoded);
    }

    /**
     * Test that navigation using the encrypted cursor works seamlessly for next and previous pages.
     */
    public function test_encrypted_cursor_navigates_pages_correctly(): void
    {
        // 1. First page
        $page1 = $this->actingAs($this->seller)->get(route('admin.orders'));
        $page1->assertOk();

        preg_match('/href="([^"]*cursor=[^"]*)"/', $page1->getContent(), $matches);
        $this->assertNotEmpty($matches);

        $nextUrl = html_entity_decode($matches[1]);
        parse_str(parse_url($nextUrl, PHP_URL_QUERY), $page2Params);

        // 2. Second page via encrypted cursor
        $page2 = $this->actingAs($this->seller)->get(route('admin.orders', $page2Params));
        $page2->assertOk();

        // Ensure page 2 content differs from page 1
        $this->assertNotEquals($page1->getContent(), $page2->getContent());

        // Ensure previous page link is generated and contains encrypted cursor
        $contentPage2 = $page2->getContent();
        $this->assertStringContainsString('cursor=', $contentPage2);
    }

    /**
     * Test that filters (e.g. status, search, date) are preserved along with the encrypted cursor.
     */
    public function test_filters_are_preserved_in_encrypted_pagination(): void
    {
        $response = $this->actingAs($this->seller)->get(route('admin.orders', [
            'status' => 'success',
            'search' => 'Buyer',
        ]));

        $response->assertOk();

        $content = $response->getContent();
        preg_match('/href="([^"]*cursor=[^"]*)"/', $content, $matches);
        $this->assertNotEmpty($matches);

        $nextUrl = html_entity_decode($matches[1]);
        parse_str(parse_url($nextUrl, PHP_URL_QUERY), $queryParams);

        $this->assertEquals('success', $queryParams['status'] ?? null);
        $this->assertEquals('Buyer', $queryParams['search'] ?? null);
        $this->assertNotEmpty($queryParams['cursor'] ?? null);
    }

    /**
     * Test that invalid or tampered cursor gracefully defaults to page 1 without throwing 500 error.
     */
    public function test_tampered_cursor_handled_gracefully(): void
    {
        $response = $this->actingAs($this->seller)->get(route('admin.orders', [
            'cursor' => 'tampered_malicious_cursor_string',
        ]));

        $response->assertOk();
    }
}
