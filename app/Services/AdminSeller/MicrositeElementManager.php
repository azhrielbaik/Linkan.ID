<?php

namespace App\Services\AdminSeller;

use App\Models\Appearance;
use App\Models\DigitalProduct;
use App\Models\DividerElement;
use App\Models\ImageElement;
use App\Models\SocialMediaElement;
use App\Models\TextElement;
use App\Models\VideoElement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

/**
 * Deep Module encapsulating all Microsite Element lifecycle operations,
 * media file management, and Appearance blocks_order invariant synchronization.
 */
class MicrositeElementManager
{
    /**
     * Polymorphic model mapping for microsite element types.
     */
    protected array $elementClasses = [
        'image' => ImageElement::class,
        'divider' => DividerElement::class,
        'text' => TextElement::class,
        'video' => VideoElement::class,
        'social' => SocialMediaElement::class,
    ];

    /**
     * Locate an element by polymorphic type, primary ID, and owner.
     */
    public function getElement(string $type, int $id, int $userId): ?Model
    {
        $modelClass = $this->elementClasses[$type] ?? null;
        if (! $modelClass) {
            return null;
        }

        return $modelClass::where('user_id', $userId)->find($id);
    }

    /**
     * Toggle element active visibility.
     */
    public function toggleVisibility(string $type, int $id, int $userId, bool $isActive): bool
    {
        $element = $this->getElement($type, $id, $userId);
        if (! $element) {
            return false;
        }

        $element->is_active = $isActive;

        return (bool) $element->save();
    }

    /**
     * Store or update a Divider element and synchronize Appearance blocks_order.
     */
    public function storeDivider(int $userId, int $appearanceId, string $type, int $size, ?int $elementId = null): DividerElement
    {
        return DB::transaction(function () use ($userId, $appearanceId, $type, $size, $elementId) {
            $element = null;
            if ($elementId) {
                $element = DividerElement::where('id', $elementId)->where('user_id', $userId)->first();
            }

            $isNew = false;
            if (! $element) {
                $element = new DividerElement;
                $element->user_id = $userId;
                $element->appearance_id = $appearanceId;
                $maxOrder = DividerElement::where('appearance_id', $appearanceId)->max('order_position');
                $element->order_position = $maxOrder ? $maxOrder + 1 : 1;
                $element->is_active = true;
                $isNew = true;
            }

            $element->type = $type;
            $element->size = $size;
            $element->save();

            if ($isNew) {
                $this->addBlockToAppearance($appearanceId, $userId, 'divider_'.$element->id);
            }

            return $element;
        });
    }

    /**
     * Store or update an Image element, convert format, and synchronize Appearance blocks_order.
     */
    public function storeImage(int $userId, int $appearanceId, ?UploadedFile $imageFile, ?string $linkUrl = null, ?int $elementId = null): ImageElement
    {
        return DB::transaction(function () use ($userId, $appearanceId, $imageFile, $linkUrl, $elementId) {
            $element = null;
            if ($elementId) {
                $element = ImageElement::where('id', $elementId)->where('user_id', $userId)->first();
            }

            $isNew = false;
            if (! $element) {
                $element = new ImageElement;
                $element->user_id = $userId;
                $element->appearance_id = $appearanceId;
                $maxOrder = ImageElement::where('appearance_id', $appearanceId)->max('order_position');
                $element->order_position = $maxOrder ? $maxOrder + 1 : 1;
                $element->is_active = true;
                $isNew = true;
            }

            if ($imageFile) {
                if ($element->image_path) {
                    Storage::disk('public')->delete($element->image_path);
                }

                $filename = 'elements/images/'.time().'_'.Str::random(10).'.webp';
                $image = ImageManager::gd()->read($imageFile)->scaleDown(width: 1200);
                $encoded = $image->toWebp(80);
                Storage::disk('public')->put($filename, (string) $encoded);

                $element->image_path = $filename;
            }

            $element->link_url = $linkUrl;
            $element->save();

            if ($isNew) {
                $this->addBlockToAppearance($appearanceId, $userId, 'image_'.$element->id);
            }

            return $element;
        });
    }

    /**
     * Store or update a Text element and synchronize Appearance blocks_order.
     */
    public function storeText(int $userId, int $appearanceId, array $data, ?UploadedFile $iconUpload = null, ?int $elementId = null): TextElement
    {
        return DB::transaction(function () use ($userId, $appearanceId, $data, $iconUpload, $elementId) {
            $element = null;
            if ($elementId) {
                $element = TextElement::where('id', $elementId)->where('user_id', $userId)->first();
            }

            $isNew = false;
            if (! $element) {
                $element = new TextElement;
                $element->user_id = $userId;
                $element->appearance_id = $appearanceId;
                $maxOrder = TextElement::where('appearance_id', $appearanceId)->max('order_position');
                $element->order_position = $maxOrder ? $maxOrder + 1 : 1;
                $element->is_active = true;
                $isNew = true;
            }

            $element->content = $data['content'] ?? null;
            $element->has_button = $data['has_button'] ?? false;
            $element->button_text = $data['button_text'] ?? null;
            $element->button_link = $data['button_link'] ?? null;
            $element->button_color = $data['button_color'] ?? null;

            if (isset($data['button_icon_type'])) {
                $element->button_icon_type = $data['button_icon_type'];

                if ($data['button_icon_type'] === 'emoji' || $data['button_icon_type'] === 'fontawesome') {
                    $element->button_icon_value = $data['button_icon_emoji'] ?? null;
                } elseif ($data['button_icon_type'] === 'url') {
                    $element->button_icon_value = $data['button_icon_url'] ?? null;
                } elseif ($data['button_icon_type'] === 'upload' && $iconUpload) {
                    if ($element->button_icon_value) {
                        Storage::disk('public')->delete($element->button_icon_value);
                    }

                    $filename = 'elements/text_icons/'.time().'_'.Str::random(10).'.webp';
                    $image = ImageManager::gd()->read($iconUpload)->scaleDown(width: 100);
                    $encoded = $image->toWebp(80);
                    Storage::disk('public')->put($filename, (string) $encoded);

                    $element->button_icon_value = $filename;
                }
            }

            $element->save();

            if ($isNew) {
                $this->addBlockToAppearance($appearanceId, $userId, 'text_'.$element->id);
            }

            return $element;
        });
    }

    /**
     * Store or update a Video element and synchronize Appearance blocks_order.
     */
    public function storeVideo(int $userId, int $appearanceId, ?string $videoUrl, bool $isAutoplay, ?int $elementId = null): VideoElement
    {
        return DB::transaction(function () use ($userId, $appearanceId, $videoUrl, $isAutoplay, $elementId) {
            $element = null;
            if ($elementId) {
                $element = VideoElement::where('id', $elementId)->where('user_id', $userId)->first();
            }

            $isNew = false;
            if (! $element) {
                $element = new VideoElement;
                $element->user_id = $userId;
                $element->appearance_id = $appearanceId;
                $element->is_active = true;
                $isNew = true;
            }

            $element->video_url = $videoUrl ?? '';
            $element->is_autoplay = $isAutoplay;
            $element->save();

            if ($isNew) {
                $this->addBlockToAppearance($appearanceId, $userId, 'video_'.$element->id);
            }

            return $element;
        });
    }

    /**
     * Store or update a Social Media element and synchronize Appearance blocks_order.
     */
    public function storeSocial(int $userId, int $appearanceId, array $platforms, ?int $elementId = null): SocialMediaElement
    {
        return DB::transaction(function () use ($userId, $appearanceId, $platforms, $elementId) {
            $element = null;
            if ($elementId) {
                $element = SocialMediaElement::where('id', $elementId)->where('user_id', $userId)->first();
            }

            $isNew = false;
            if (! $element) {
                $element = new SocialMediaElement;
                $element->user_id = $userId;
                $element->appearance_id = $appearanceId;
                $element->is_active = true;
                $isNew = true;
            }

            $element->platforms = $platforms;
            $element->save();

            if ($isNew) {
                $this->addBlockToAppearance($appearanceId, $userId, 'social_'.$element->id);
            }

            return $element;
        });
    }

    /**
     * Destroy any element type, clean up storage assets, and purge its block key from Appearance atomically.
     */
    public function destroy(string $type, int $id, int $userId, ?int $appearanceId = null): bool
    {
        if ($type === 'digitalproduct') {
            return $this->destroyDigitalProduct($id, $userId, $appearanceId);
        }

        $modelClass = $this->elementClasses[$type] ?? null;
        if (! $modelClass) {
            return false;
        }

        return DB::transaction(function () use ($modelClass, $type, $id, $userId, $appearanceId) {
            $element = $modelClass::where('id', $id)->where('user_id', $userId)->first();
            if (! $element) {
                return false;
            }

            // Cleanup media files from storage
            if (isset($element->image_path) && $element->image_path) {
                Storage::disk('public')->delete($element->image_path);
            }
            if (isset($element->button_icon_value) && $element->button_icon_type === 'upload') {
                Storage::disk('public')->delete($element->button_icon_value);
            }

            $resolvedAppearanceId = $appearanceId ?? $element->appearance_id ?? null;
            $element->delete();

            // Synchronize Appearance blocks_order invariant
            if ($resolvedAppearanceId) {
                $appearance = Appearance::where('id', $resolvedAppearanceId)->where('user_id', $userId)->first();
                if ($appearance) {
                    $appearance->removeBlock("{$type}_{$id}");
                    if ($type === 'video') {
                        $appearance->removeBlock("videoBlock_{$id}");
                    }
                }
            }

            return true;
        });
    }

    /**
     * Unpin or delete digital product reference from Appearance blocks_order.
     */
    protected function destroyDigitalProduct(int $productId, int $userId, ?int $appearanceId = null): bool
    {
        $product = DigitalProduct::where('id', $productId)->where('user_id', $userId)->first();
        if (! $product) {
            return false;
        }

        if ($appearanceId) {
            $appearance = Appearance::where('id', $appearanceId)->where('user_id', $userId)->first();
            if ($appearance) {
                $appearance->removeBlock('digitalproduct_'.$product->id);
            }
        }

        return true;
    }

    /**
     * Append block key to Appearance blocks_order if not already present.
     */
    public function addBlockToAppearance(int $appearanceId, int $userId, string $blockKey): bool
    {
        $appearance = Appearance::where('id', $appearanceId)->where('user_id', $userId)->first();
        if (! $appearance) {
            return false;
        }

        $appearance->addBlock($blockKey);

        return true;
    }

    /**
     * Remove block key from Appearance blocks_order.
     */
    public function removeBlockFromAppearance(int $appearanceId, int $userId, string $blockKey): bool
    {
        $appearance = Appearance::where('id', $appearanceId)->where('user_id', $userId)->first();
        if (! $appearance) {
            return false;
        }

        $appearance->removeBlock($blockKey);

        return true;
    }
}

