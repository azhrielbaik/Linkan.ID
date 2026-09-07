<?php

namespace App\Services;

use App\Models\Appearance;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Http\UploadedFile;

class AppearanceService
{
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
        
        $image = Image::decode($file)->scaleDown(width: $scaleWidth);
        $encoded = $image->encodeUsingFileExtension('webp', quality: 80);
            
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
        $imageBase64 = base64_decode($imageParts[1]);

        $path = $directory . '/' . time() . '_' . Str::random(10) . '.webp';
        
        $image = Image::decode($imageBase64)->scaleDown(width: $scaleWidth);
        $encoded = $image->encodeUsingFileExtension('webp', quality: 80);
            
        Storage::disk('public')->put($path, (string) $encoded);

        return $path;
    }
}
