<?php

namespace Tests\Unit;

use App\Models\Appearance;
use App\Models\DividerElement;
use App\Models\ImageElement;
use App\Models\SocialMediaElement;
use App\Models\TextElement;
use App\Models\User;
use App\Models\VideoElement;
use App\Services\AdminSeller\MicrositeElementManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MicrositeElementManagerTest extends TestCase
{
    use RefreshDatabase;

    protected MicrositeElementManager $manager;
    protected User $user;
    protected Appearance $appearance;

    protected function setUp(): void
    {
        parent::setUp();

        $this->manager = new MicrositeElementManager;
        $this->user = User::factory()->create();
        $this->appearance = Appearance::create([
            'user_id' => $this->user->id,
            'name' => 'Store Name',
            'alias' => 'store-alias',
            'blocks_order' => 'profile',
        ]);

        Storage::fake('public');
    }

    public function test_can_store_and_destroy_divider_with_blocks_order_sync(): void
    {
        // 1. Store divider
        $divider = $this->manager->storeDivider(
            $this->user->id,
            $this->appearance->id,
            'line',
            20
        );

        $this->assertInstanceOf(DividerElement::class, $divider);
        $this->assertEquals(20, $divider->size);

        // Assert Appearance blocks_order contains new block key
        $this->appearance->refresh();
        $this->assertContains('divider_'.$divider->id, $this->appearance->getBlocksOrderArray());

        // 2. Destroy divider
        $result = $this->manager->destroy('divider', $divider->id, $this->user->id);
        $this->assertTrue($result);
        $this->assertDatabaseMissing('divider_elements', ['id' => $divider->id]);

        // Assert block key was atomically evicted from blocks_order
        $this->appearance->refresh();
        $this->assertNotContains('divider_'.$divider->id, $this->appearance->getBlocksOrderArray());
    }

    public function test_can_store_and_destroy_image_with_storage_and_order_cleanup(): void
    {
        $fakeImage = UploadedFile::fake()->image('test.jpg', 640, 480);

        // 1. Store Image
        $imageEl = $this->manager->storeImage(
            $this->user->id,
            $this->appearance->id,
            $fakeImage,
            'https://example.com/promo'
        );

        $this->assertInstanceOf(ImageElement::class, $imageEl);
        $this->assertNotNull($imageEl->image_path);
        Storage::disk('public')->assertExists($imageEl->image_path);

        $this->appearance->refresh();
        $this->assertContains('image_'.$imageEl->id, $this->appearance->getBlocksOrderArray());

        // 2. Destroy Image
        $result = $this->manager->destroy('image', $imageEl->id, $this->user->id);
        $this->assertTrue($result);
        $this->assertDatabaseMissing('image_elements', ['id' => $imageEl->id]);

        // Assert disk file is deleted and blocks_order pruned
        Storage::disk('public')->assertMissing($imageEl->image_path);
        $this->appearance->refresh();
        $this->assertNotContains('image_'.$imageEl->id, $this->appearance->getBlocksOrderArray());
    }

    public function test_can_store_and_destroy_text_element_with_icon_upload_cleanup(): void
    {
        $fakeIcon = UploadedFile::fake()->image('icon.png', 100, 100);

        $textEl = $this->manager->storeText(
            $this->user->id,
            $this->appearance->id,
            [
                'content' => 'Promotional Text Content',
                'has_button' => true,
                'button_text' => 'Click Me',
                'button_link' => 'https://linkan.id',
                'button_icon_type' => 'upload',
            ],
            $fakeIcon
        );

        $this->assertInstanceOf(TextElement::class, $textEl);
        $this->assertNotNull($textEl->button_icon_value);
        Storage::disk('public')->assertExists($textEl->button_icon_value);

        $this->appearance->refresh();
        $this->assertContains('text_'.$textEl->id, $this->appearance->getBlocksOrderArray());

        // Destroy text element
        $result = $this->manager->destroy('text', $textEl->id, $this->user->id);
        $this->assertTrue($result);
        $this->assertDatabaseMissing('text_elements', ['id' => $textEl->id]);

        Storage::disk('public')->assertMissing($textEl->button_icon_value);
        $this->appearance->refresh();
        $this->assertNotContains('text_'.$textEl->id, $this->appearance->getBlocksOrderArray());
    }

    public function test_can_store_and_destroy_video_and_social_elements(): void
    {
        // Video
        $video = $this->manager->storeVideo($this->user->id, $this->appearance->id, 'https://youtube.com/watch?v=123', true);
        $this->appearance->refresh();
        $this->assertContains('video_'.$video->id, $this->appearance->getBlocksOrderArray());

        $this->manager->destroy('video', $video->id, $this->user->id);
        $this->appearance->refresh();
        $this->assertNotContains('video_'.$video->id, $this->appearance->getBlocksOrderArray());

        // Social
        $social = $this->manager->storeSocial($this->user->id, $this->appearance->id, ['instagram' => 'linkan_id']);
        $this->appearance->refresh();
        $this->assertContains('social_'.$social->id, $this->appearance->getBlocksOrderArray());

        $this->manager->destroy('social', $social->id, $this->user->id);
        $this->appearance->refresh();
        $this->assertNotContains('social_'.$social->id, $this->appearance->getBlocksOrderArray());
    }

    public function test_toggle_visibility_updates_active_state(): void
    {
        $divider = $this->manager->storeDivider($this->user->id, $this->appearance->id, 'space', 10);
        $this->assertTrue($divider->is_active);

        $this->manager->toggleVisibility('divider', $divider->id, $this->user->id, false);
        $this->assertFalse($divider->fresh()->is_active);

        $this->manager->toggleVisibility('divider', $divider->id, $this->user->id, true);
        $this->assertTrue($divider->fresh()->is_active);
    }
}

