<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppearanceRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan membuat request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Dapatkan aturan validasi untuk request.
     */
    public function rules(): array
    {
        return [
            'appearance_id' => 'required|integer|exists:appearances,id',
            'name' => [
                'required', 
                'string', 
                function ($attribute, $value, $fail) {
                    if (str_word_count(strip_tags(html_entity_decode($value))) > 50) {
                        $fail('Nama profil tidak boleh lebih dari 50 kata.');
                    }
                }
            ],
            'bio' => [
                'nullable', 
                'string', 
                function ($attribute, $value, $fail) {
                    if (str_word_count(strip_tags(html_entity_decode($value))) > 250) {
                        $fail('Deskripsi / Bio profil tidak boleh lebih dari 250 kata.');
                    }
                }
            ],
            'banner'               => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'profile_image'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'profile_image_base64' => 'nullable|string',
            'delete_banner'        => 'nullable',
            'delete_profile_image' => 'nullable',
            'profile_shape'        => 'nullable|string|in:circle,rounded,square',
            'theme_color'          => 'nullable|string|max:7',
            'background_color'     => 'nullable|string',
            'background_type'      => 'nullable|string|in:color,image',
            'profile_layout'       => 'nullable|string|in:title-top,classic,side',
            'block_shape'          => 'nullable|string|in:sharp,rounded,pill',
            'instagram'            => 'nullable|url|max:255',
            'tiktok'               => 'nullable|url|max:255',
            'whatsapp'             => 'nullable|url|max:255',
            'linkedin'             => 'nullable|url|max:255',
            'facebook'             => 'nullable|url|max:255',
            'website'              => 'nullable|url|max:255',
            'twitter'              => 'nullable|url|max:255',
            'youtube'              => 'nullable|url|max:255',
            'telegram'             => 'nullable|url|max:255',
            'email'                => 'nullable|email|max:255',
            'discord'              => 'nullable|url|max:255',
        ];
    }
}
