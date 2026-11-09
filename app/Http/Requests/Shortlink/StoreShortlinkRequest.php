<?php

namespace App\Http\Requests\Shortlink;

use Illuminate\Foundation\Http\FormRequest;

class StoreShortlinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'slug' => 'required|alpha_dash|unique:shortlinks,slug',
            'destination' => 'required|url',
        ];
    }
}
