<?php

namespace App\Http\Requests\Shortlink;

use App\Models\Shortlink;
use Illuminate\Foundation\Http\FormRequest;

class UpdateShortlinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $shortlink = $this->route('shortlink');
        $shortlinkId = $shortlink instanceof Shortlink ? $shortlink->id : $shortlink;

        return [
            'title' => 'nullable|string|max:255',
            'slug' => 'required|alpha_dash|unique:shortlinks,slug,'.$shortlinkId,
            'password' => 'nullable|string',
            'expires_at' => 'nullable|date',
        ];
    }
}
