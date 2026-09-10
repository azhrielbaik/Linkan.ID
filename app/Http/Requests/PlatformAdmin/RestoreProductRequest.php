<?php

namespace App\Http\Requests\PlatformAdmin;

use Illuminate\Foundation\Http\FormRequest;

class RestoreProductRequest extends FormRequest
{
    /**
     * Hanya admin platform yang diizinkan memulihkan produk.
     */
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->hasRole('admin_platform');
    }

    /**
     * Aturan validasi untuk pemulihan produk yang di-takedown.
     */
    public function rules(): array
    {
        return [
            'restore_reason' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Pesan error kustom.
     */
    public function messages(): array
    {
        return [
            'restore_reason.max' => 'Catatan pemulihan maksimal 1000 karakter.',
        ];
    }
}
