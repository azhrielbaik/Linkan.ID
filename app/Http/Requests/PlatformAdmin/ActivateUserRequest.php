<?php

namespace App\Http\Requests\PlatformAdmin;

use Illuminate\Foundation\Http\FormRequest;

class ActivateUserRequest extends FormRequest
{
    /**
     * Hanya admin platform yang diizinkan mengaktifkan user.
     */
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->hasRole('admin_platform');
    }

    /**
     * Aturan validasi untuk mengaktifkan kembali akun yang di-suspend.
     */
    public function rules(): array
    {
        return [
            'activate_reason' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Pesan error kustom.
     */
    public function messages(): array
    {
        return [
            'activate_reason.max' => 'Catatan aktivasi maksimal 1000 karakter.',
        ];
    }
}
