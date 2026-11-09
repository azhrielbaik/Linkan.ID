<?php

namespace App\Http\Requests\PublicDispute;

use Illuminate\Foundation\Http\FormRequest;

class StoreDisputeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required|string',
            'buyer_email' => 'required|email',
            'buyer_name' => 'required|string|max:100',
            'buyer_phone' => 'nullable|string|max:25',
            'reason' => 'required|in:broken_link,corrupted_file,misleading_description,fraud_scam,other',
            'description' => 'required|string|min:20|max:2000',
            'evidence_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,webp|max:5120',
            'refund_bank_name' => 'required|string|max:50',
            'refund_account_name' => 'required|string|max:100',
            'refund_account_number' => 'required|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'description.min' => 'Mohon jelaskan kendala produk secara detail (minimal 20 karakter).',
            'evidence_file.max' => 'Ukuran berkas bukti maksimal adalah 5MB.',
            'evidence_file.mimes' => 'Format berkas bukti harus berupa gambar (JPG, PNG, WEBP) atau PDF.',
        ];
    }
}
