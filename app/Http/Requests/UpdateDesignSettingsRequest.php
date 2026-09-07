<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Appearance;
use Illuminate\Support\Facades\Auth;

class UpdateDesignSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Otorisasi: Pastikan appearance_id yang di-request adalah milik user yang sedang login
        $appearanceId = $this->input('appearance_id');
        
        if (!$appearanceId) {
            return false;
        }

        return Appearance::where('id', $appearanceId)
                         ->where('user_id', Auth::id())
                         ->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'appearance_id'    => 'required|integer|exists:appearances,id',
            'background_type'  => 'nullable|string|in:color,image',
            'background_color' => 'nullable|string|max:100',
            'profile_layout'   => 'nullable|string|in:classic,title-top,side',
            'block_shape'      => 'nullable|string|in:sharp,rounded,pill',
        ];
    }
}
