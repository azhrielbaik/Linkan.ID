<?php

namespace Tests\Unit;

use App\Models\Appearance;
use PHPUnit\Framework\TestCase;

class AppearanceTest extends TestCase
{
    public function test_get_digital_product_ids_returns_empty_array_when_blocks_order_is_empty(): void
    {
        $appearance = new Appearance;
        $appearance->blocks_order = '';
        $this->assertSame([], $appearance->getDigitalProductIds());

        $appearance->blocks_order = null;
        $this->assertSame([], $appearance->getDigitalProductIds());
    }

    public function test_get_digital_product_ids_extracts_unique_ids_from_comma_separated_string(): void
    {
        $appearance = new Appearance;
        $appearance->blocks_order = 'profile,digitalproduct_18,text_1,digitalproduct_17,digitalproduct_18,video_2';

        $productIds = $appearance->getDigitalProductIds();

        $this->assertSame([18, 17], $productIds);
    }

    public function test_get_digital_product_ids_extracts_unique_ids_from_array(): void
    {
        $appearance = new Appearance;
        $appearance->blocks_order = ['profile', 'digitalproduct_5', 'divider_1', 'digitalproduct_12'];

        $productIds = $appearance->getDigitalProductIds();

        $this->assertSame([5, 12], $productIds);
    }

    public function test_get_digital_product_ids_ignores_non_product_or_invalid_blocks(): void
    {
        $appearance = new Appearance;
        $appearance->blocks_order = 'profile,image_4,text_3,video_3,divider_8,digitalproduct_0,digitalproduct_abc';

        $productIds = $appearance->getDigitalProductIds();

        $this->assertSame([], $productIds);
    }
}
