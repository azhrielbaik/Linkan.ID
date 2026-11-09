<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => 'required|string|size:64',
            'otp_code' => 'required|string|size:4',
        ];
    }

    public function messages(): array
    {
        return [
            'otp_code.required' => 'Please enter the 4-digit code.',
            'otp_code.size' => 'The verification code must be 4 digits.',
        ];
    }
}
