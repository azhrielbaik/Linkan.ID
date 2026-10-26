<?php

namespace App\Http\Requests\AdminSeller;

use Illuminate\Foundation\Http\FormRequest;

class SavePayoutMethodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'method_type' => 'required|string|in:Bank,DANA,ShopeePay',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'bank_name' => 'nullable|string|max:255',
        ];
    }
}
