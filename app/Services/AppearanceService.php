<?php

namespace App\Services;

use App\Models\Appearance;
use App\Models\User;
use App\Models\ImageElement;
use App\Models\DividerElement;
use App\Models\TextElement;
use App\Models\VideoElement;
use App\Models\SocialMediaElement;
use App\Models\DigitalProduct;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Illuminate\Http\UploadedFile;

class AppearanceService
{
    /**
     * Menyusun urutan elemen microsite untuk tampilan publik.
     * Mengembalikan array structured blocks yang siap di-render langsung oleh Blade view.
     *
     * @param Appearance $appearance
     * @param User $user
     * @return array
     */
    public function getSortedBlocksForPublic(Appearance $appearance, User $user): array
    {
        $blocksOrder = [];
        if ($appearance && $appearance->blocks_order) {
            $blocksOrder = explode(',', $appearance->blocks_order);
        } else {
            $blocksOrder = ['profile'];
        }

        // Ambil elemen-elemen aktif per appearance
        $imageElements = ImageElement::where('appearance_id', $appearance->id)->where('is_active', true)->get()->keyBy('id');
        $dividerElements = DividerElement::where('appearance_id', $appearance->id)->where('is_active', true)->get()->keyBy('id');
        $textElements = TextElement::where('appearance_id', $appearance->id)->where('is_active', true)->get()->keyBy('id');
        $videoElements = VideoElement::where('appearance_id', $appearance->id)->where('is_active', true)->get()->keyBy('id');
        $socialMediaElements = SocialMediaElement::where('appearance_id', $appearance->id)->where('is_active', true)->get()->keyBy('id');

        // Append missing elements to ensure they always render even if blocks_order is out of sync
        foreach ($imageElements as $el) {
            $id = 'image_' . $el->id;
            if (!in_array($id, $blocksOrder)) {
                $blocksOrder[] = $id;
            }
        }
        foreach ($dividerElements as $el) {
            $id = 'divider_' . $el->id;
            if (!in_array($id, $blocksOrder)) {
                $blocksOrder[] = $id;
            }
        }
        foreach ($textElements as $el) {
            $id = 'text_' . $el->id;
            if (!in_array($id, $blocksOrder)) {
                $blocksOrder[] = $id;
            }
        }
        foreach ($videoElements as $el) {
            $id = 'video_' . $el->id;
            if (!in_array($id, $blocksOrder)) {
                $blocksOrder[] = $id;
            }
        }
        foreach ($socialMediaElements as $el) {
            $id = 'social_' . $el->id;
            if (!in_array($id, $blocksOrder)) {
                $blocksOrder[] = $id;
            }
        }

        // Ambil digital products yang ada di blocksOrder
        $productIds = [];
        foreach ($blocksOrder as $block) {
            if (str_starts_with($block, 'digitalproduct_')) {
                $productIds[] = str_replace('digitalproduct_', '', $block);
            }
        }
        $products = DigitalProduct::where('user_id', $user->id)
            ->whereIn('id', $productIds)
            ->where('is_active', 1)
            ->get()
            ->keyBy('id');

        $sortedBlocks = [];

        foreach ($blocksOrder as $blockId) {
            if ($blockId === 'profile') {
                $sortedBlocks[] = [
                    'id'   => 'profile',
                    'type' => 'profile',
                    'data' => $appearance,
                ];
            } elseif (str_starts_with($blockId, 'image_')) {
                $elId = str_replace('image_', '', $blockId);
                $imageEl = $imageElements->get($elId);
                if ($imageEl && $imageEl->image_path) {
                    $sortedBlocks[] = [
                        'id'   => $blockId,
                        'type' => 'image',
                        'data' => $imageEl,
                    ];
                }
            } elseif (str_starts_with($blockId, 'divider_')) {
                $elId = str_replace('divider_', '', $blockId);
                $dividerEl = $dividerElements->get($elId);
                if ($dividerEl) {
                    $sortedBlocks[] = [
                        'id'      => $blockId,
                        'type'    => 'divider',
                        'data'    => $dividerEl,
                        'padding' => $dividerEl->type === 'line' ? ($dividerEl->size / 2) . 'px 0' : '0',
                        'height'  => $dividerEl->type === 'line' ? '0' : $dividerEl->size . 'px',
                        'border'  => $dividerEl->type === 'line' ? '2px solid #cbd5e1' : 'none',
                    ];
                }
            } elseif (str_starts_with($blockId, 'text_')) {
                $elId = str_replace('text_', '', $blockId);
                $textEl = $textElements->get($elId);
                if ($textEl) {
                    $sortedBlocks[] = [
                        'id'   => $blockId,
                        'type' => 'text',
                        'data' => $textEl,
                    ];
                }
            } elseif (str_starts_with($blockId, 'video_')) {
                $elId = str_replace('video_', '', $blockId);
                $videoEl = $videoElements->get($elId);
                if ($videoEl && $videoEl->video_url) {
                    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|shorts)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $videoEl->video_url, $match);
                    $videoId = $match[1] ?? '';
                    $autoplay = $videoEl->is_autoplay ? '&autoplay=1&mute=1' : '';
                    $embedUrl = $videoId ? "https://www.youtube.com/embed/{$videoId}?rel=0{$autoplay}" : '';
                    if ($embedUrl) {
                        $sortedBlocks[] = [
                            'id'        => $blockId,
                            'type'      => 'video',
                            'data'      => $videoEl,
                            'embed_url' => $embedUrl,
                        ];
                    }
                }
            } elseif (str_starts_with($blockId, 'social_')) {
                $elId = str_replace('social_', '', $blockId);
                $socialEl = $socialMediaElements->get($elId);
                if ($socialEl) {
                    $platforms = is_string($socialEl->platforms) ? json_decode($socialEl->platforms, true) : ($socialEl->platforms ?? []);
                    $sortedBlocks[] = [
                        'id'        => $blockId,
                        'type'      => 'social',
                        'data'      => $socialEl,
                        'platforms' => $platforms,
                    ];
                }
            } elseif (str_starts_with($blockId, 'digitalproduct_')) {
                $elId = str_replace('digitalproduct_', '', $blockId);
                $digitalProduct = $products->get($elId);
                if ($digitalProduct && ($digitalProduct->is_active ?? true)) {
                    $mediaFiles = is_string($digitalProduct->media_files) ? json_decode($digitalProduct->media_files, true) : ($digitalProduct->media_files ?? []);
                    $media = [];
                    foreach ($mediaFiles as $file) {
                        if (is_array($file)) {
                            $media[] = [
                                'type' => $file['type'] ?? 'image/jpeg',
                                'url'  => isset($file['path']) ? asset('storage/' . $file['path']) : ($file['url'] ?? '')
                            ];
                        }
                    }

                    $productData = [
                        'id' => $digitalProduct->id,
                        'title' => $digitalProduct->title,
                        'description' => $digitalProduct->description,
                        'pricing' => [
                            'type'  => $digitalProduct->pricing_type,
                            'fixed' => $digitalProduct->price,
                            'min'   => $digitalProduct->price_min,
                            'max'   => $digitalProduct->price_max,
                        ],
                        'quantity' => [
                            'min' => $digitalProduct->quantity_min ?? 1,
                            'max' => $digitalProduct->has_quantity_limit ? $digitalProduct->quantity : null,
                        ],
                        'schedule' => [
                            'enabled' => $digitalProduct->is_scheduled,
                            'start'   => $digitalProduct->start_time,
                            'end'     => $digitalProduct->end_time,
                        ],
                        'deliverable' => [
                            'type' => $digitalProduct->deliverable_type ?? 'external',
                            'url'  => $digitalProduct->deliverable_type !== 'upload' ? $digitalProduct->deliverable_url : '',
                            'file' => $digitalProduct->deliverable_type === 'upload' ? $digitalProduct->deliverable_url : ''
                        ]
                    ];

                    $sortedBlocks[] = [
                        'id'           => $blockId,
                        'type'         => 'digitalproduct',
                        'data'         => $digitalProduct,
                        'product_data' => $productData,
                        'media'        => $media,
                    ];
                }
            }
        }

        return $sortedBlocks;
    }
    /**
     * Memproses dan menyimpan data Appearance.
     */
    public function updateAppearance(
        User $user, 
        array $payload, 
        ?UploadedFile $bannerFile = null, 
        ?UploadedFile $profileFile = null
    ): Appearance {
        $appearance = Appearance::where('user_id', $user->id)->findOrFail($payload['appearance_id']);

        // 1. Eksekusi Penghapusan Banner
        if (isset($payload['delete_banner']) && $payload['delete_banner'] == 1) {
            $this->deleteFile($appearance->banner);
            $appearance->banner = null;
        }

        // 2. Eksekusi Penghapusan Profile Image
        if (isset($payload['delete_profile_image']) && $payload['delete_profile_image'] == 1) {
            $this->deleteFile($appearance->profile_image);
            $appearance->profile_image = null;
        }

        // 3. Proses Upload Banner
        if ($bannerFile) {
            $this->deleteFile($appearance->banner);
            $appearance->banner = $this->processAndStoreImage($bannerFile, 'appearances/banners', 1200);
        }

        // 4. Proses Upload Profile Image (Prioritas Base64, lalu File)
        if (!empty($payload['profile_image_base64'])) {
            $this->deleteFile($appearance->profile_image);
            $appearance->profile_image = $this->processAndStoreBase64Image($payload['profile_image_base64'], 'appearances/profiles', 500);
        } elseif ($profileFile) {
            $this->deleteFile($appearance->profile_image);
            $appearance->profile_image = $this->processAndStoreImage($profileFile, 'appearances/profiles', 500);
        }

        // 5. Assignment Data Teks & Pengaturan Tampilan
        $appearance->name = $payload['name'];
        $appearance->bio = $payload['bio'] ?? null;
        
        if (isset($payload['profile_shape'])) $appearance->profile_shape = $payload['profile_shape'];
        
        $appearance->theme_color = !empty($payload['theme_color']) ? $payload['theme_color'] : ($appearance->theme_color ?? '#FF9040');
        $appearance->background_color = !empty($payload['background_color']) ? $payload['background_color'] : ($appearance->background_color ?? '#FFFFFF');
        
        if (isset($payload['background_type'])) $appearance->background_type = $payload['background_type'];
        if (isset($payload['profile_layout'])) $appearance->profile_layout = $payload['profile_layout'];
        if (isset($payload['block_shape'])) $appearance->block_shape = $payload['block_shape'];

        $appearance->instagram = $payload['instagram'] ?? null;
        $appearance->tiktok = $payload['tiktok'] ?? null;
        $appearance->whatsapp = $payload['whatsapp'] ?? null;
        $appearance->linkedin = $payload['linkedin'] ?? null;
        $appearance->facebook = $payload['facebook'] ?? null;
        $appearance->website = $payload['website'] ?? null;
        $appearance->twitter = $payload['twitter'] ?? null;
        $appearance->youtube = $payload['youtube'] ?? null;
        $appearance->telegram = $payload['telegram'] ?? null;
        $appearance->email = $payload['email'] ?? null;
        $appearance->discord = $payload['discord'] ?? null;

        $appearance->is_active = true;

        $appearance->save();

        return $appearance;
    }

    /**
     * Hapus file secara aman dari Storage publik.
     */
    private function deleteFile(?string $path): void
    {
        if ($path && Storage::exists('public/' . $path)) {
            Storage::delete('public/' . $path);
        }
    }

    /**
     * Decode file menggunakan Intervention Image -> scaleDown -> save webp
     */
    private function processAndStoreImage(UploadedFile $file, string $directory, int $scaleWidth): string
    {
        $path = $directory . '/' . time() . '_' . Str::random(10) . '.webp';
        
        $image = ImageManager::gd()->read($file)->scaleDown(width: $scaleWidth);
        $encoded = $image->toWebp(80);
            
        Storage::disk('public')->put($path, (string) $encoded);

        return $path;
    }

    /**
     * Decode base64 menggunakan Intervention Image -> scaleDown -> save webp
     */
    private function processAndStoreBase64Image(string $base64String, string $directory, int $scaleWidth): string
    {
        // Ekstrak data base64 murni tanpa awalan 'data:image/...;base64,'
        $imageParts = explode(";base64,", $base64String);
        $imageBase64 = base64_decode(end($imageParts));

        $path = $directory . '/' . time() . '_' . Str::random(10) . '.webp';
        
        $image = ImageManager::gd()->read($imageBase64)->scaleDown(width: $scaleWidth);
        $encoded = $image->toWebp(80);
            
        Storage::disk('public')->put($path, (string) $encoded);

        return $path;
    }
}
