<?php

namespace App\Http\Requests\PlatformAdmin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        // Hanya admin platform yang dapat mengubah pengaturan
        return auth()->check() && auth()->user()->hasRole('admin_platform');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'commission_percent' => 'required|numeric|min:0|max:100',
            'min_withdraw'       => 'required|numeric|min:0',
            'admin_password'     => 'required|string',
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages()
    {
        return [
            'commission_percent.required' => 'Persentase komisi wajib diisi.',
            'commission_percent.numeric'  => 'Persentase komisi harus berupa angka.',
            'commission_percent.min'      => 'Persentase komisi minimal 0%.',
            'commission_percent.max'      => 'Persentase komisi maksimal 100%.',
            'min_withdraw.required'       => 'Batas minimum penarikan wajib diisi.',
            'min_withdraw.numeric'        => 'Batas minimum penarikan harus berupa angka.',
            'min_withdraw.min'            => 'Batas minimum penarikan minimal Rp 0.',
            'admin_password.required'     => 'Password admin wajib dimasukkan untuk konfirmasi keamanan.',
        ];
    }
}
