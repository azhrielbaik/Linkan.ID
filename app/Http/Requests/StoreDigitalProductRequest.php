<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDigitalProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'platform_type' => 'required|string|in:upload,dropbox,gdrive,other',
            'platform_url' => 'nullable|url|required_if:platform_type,dropbox,gdrive,other',
            'platform_file' => 'nullable|file|mimes:pdf,zip,rar|required_if:platform_type,upload',
            'price_raw' => 'required|numeric',
            'sale_price_raw' => 'nullable|numeric',
            'has_quantity_limit' => 'nullable|boolean',
            'quantity' => 'nullable|integer|required_if:has_quantity_limit,1',
            'button_text' => 'required|string',
        ];
    }
}
