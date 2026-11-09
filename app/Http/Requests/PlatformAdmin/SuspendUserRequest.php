<?php

namespace App\Http\Requests\PlatformAdmin;

use Illuminate\Foundation\Http\FormRequest;

class SuspendUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasRole('admin_platform');
    }

    public function rules(): array
    {
        return [
            'duration' => ['required', 'string', 'in:1_day,3_days,7_days,30_days,permanent'],
            'suspend_reason' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
