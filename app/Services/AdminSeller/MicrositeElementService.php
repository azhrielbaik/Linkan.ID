<?php

namespace App\Services\AdminSeller;

use App\Models\Appearance;
use App\Models\DividerElement;
use App\Models\ImageElement;
use App\Models\SocialMediaElement;
use App\Models\TextElement;
use App\Models\VideoElement;
use Illuminate\Database\Eloquent\Model;

class MicrositeElementService
{
    /**
     * Map of supported element types to their respective model classes.
     */
    protected array $elementModels = [
        'image' => ImageElement::class,
        'divider' => DividerElement::class,
        'text' => TextElement::class,
        'video' => VideoElement::class,
        'social' => SocialMediaElement::class,
    ];

    /**
     * Find element by type, ID and owner.
     */
    public function getElement(string $type, int $id, int $userId): ?Model
    {
        $modelClass = $this->elementModels[$type] ?? null;
        if (! $modelClass) {
            return null;
        }

        return $modelClass::where('user_id', $userId)->find($id);
    }

    /**
     * Toggle element active status.
     */
    public function toggleVisibility(string $type, int $id, int $userId, bool $isActive): bool
    {
        $element = $this->getElement($type, $id, $userId);
        if (! $element) {
            return false;
        }

        $element->is_active = $isActive;

        return $element->save();
    }

    /**
     * Remove block key from appearance order and persist.
     */
    public function removeBlockFromAppearance(int $appearanceId, int $userId, string $blockKey): bool
    {
        $appearance = Appearance::where('id', $appearanceId)
            ->where('user_id', $userId)
            ->first();

        if (! $appearance) {
            return false;
        }

        $appearance->removeBlock($blockKey);

        return true;
    }

    /**
     * Add block key to appearance order.
     */
    public function addBlockToAppearance(int $appearanceId, int $userId, string $blockKey): bool
    {
        $appearance = Appearance::where('id', $appearanceId)
            ->where('user_id', $userId)
            ->first();

        if (! $appearance) {
            return false;
        }

        $appearance->addBlock($blockKey);

        return true;
    }
}
