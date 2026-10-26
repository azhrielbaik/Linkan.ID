<?php

namespace App\Http\Requests\AdminSeller;

use App\Models\PlatformSetting;
use App\Services\AdminSeller\PayoutService;
use Illuminate\Foundation\Http\FormRequest;

class PayoutWithdrawRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $user = $this->user();
        $payoutService = app(PayoutService::class);
        $currentBalance = (float) $payoutService->getPayoutOverview($user)['currentBalance'];
        $minWithdraw = (float) PlatformSetting::get('min_withdraw_amount', 10000);

        return [
            'amount_raw' => ['required', 'numeric', 'min:'.$minWithdraw, 'max:'.$currentBalance],
            'method' => 'required|string|in:Bank,DANA,ShopeePay',
            'account_detail' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        $minWithdraw = (float) PlatformSetting::get('min_withdraw_amount', 10000);

        return [
            'amount_raw.min' => 'Jumlah penarikan minimal Rp '.number_format($minWithdraw, 0, ',', '.').'.',
            'amount_raw.max' => 'Jumlah penarikan melebihi saldo yang tersedia.',
            'method.required' => 'Metode penarikan wajib diisi.',
            'method.in' => 'Metode penarikan tidak valid.',
            'account_detail.required' => 'Detail akun/nomor telepon wajib diisi.',
            'account_detail.string' => 'Detail akun/nomor telepon harus berupa teks.',
            'account_detail.max' => 'Detail akun/nomor telepon terlalu panjang.',
            'account_name.required' => 'Nama akun wajib diisi.',
            'account_name.string' => 'Nama akun harus berupa teks.',
            'account_name.max' => 'Nama akun terlalu panjang.',
        ];
    }
}
