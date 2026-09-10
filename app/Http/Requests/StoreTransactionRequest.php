<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'order_id' => 'required|string',
            'transaction_status' => 'required|string',
            'product_id' => 'required|integer|exists:digital_products,id',
            'buyer_email' => 'required|email',
            'buyer_name' => 'required|string',
            'qty' => 'required|integer|min:1',
            'total_price' => 'required|numeric'
        ];
    }
}
