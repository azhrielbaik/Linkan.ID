<?php

namespace App\Http\Requests\PlatformAdmin;

use Illuminate\Foundation\Http\FormRequest;

class ApproveAppealRequest extends FormRequest
{
    /**
     * Hanya admin platform yang diizinkan menyetujui banding.
     */
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->hasRole('admin_platform');
    }

    /**
     * Aturan validasi untuk persetujuan banding akun.
     */
    public function rules(): array
    {
        return [
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Pesan error kustom.
     */
    public function messages(): array
    {
        return [
            'admin_notes.max' => 'Catatan admin maksimal 1000 karakter.',
        ];
    }
}
