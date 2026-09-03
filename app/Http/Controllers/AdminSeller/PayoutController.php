<?php

namespace App\Http\Controllers\AdminSeller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AdminSeller\PayoutService;
use App\Models\PlatformSetting;

class PayoutController extends Controller
{
    protected $payoutService;

    public function __construct(PayoutService $payoutService)
    {
        $this->payoutService = $payoutService;
    }

    public function index()
    {
        $user = Auth::user();
        $data = $this->payoutService->getPayoutOverview($user);

        return view('admin_seller.features.payouts.index', $data);
    }

    public function showWithdrawForm()
    {
        $user = Auth::user();
        $data = $this->payoutService->getWithdrawFormSettings($user);

        return view('admin_seller.features.payouts.withdraw', $data);
    }

    public function showPayoutMethodForm()
    {
        $user = Auth::user();
        $data = $this->payoutService->getWithdrawFormSettings($user);
        
        return view('admin_seller.features.payouts.method', [
            'payoutDetail' => $data['payoutDetail']
        ]);
    }

    public function savePayoutMethod(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'method_type' => 'required|string|in:Bank,DANA,ShopeePay',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'bank_name' => 'nullable|string|max:255',
        ]);

        $message = $this->payoutService->savePayoutMethod($user, $request->all());

        return redirect()->route('admin.payout.index')->with('success', $message);
    }

    public function processWithdrawal(Request $request)
    {
        $user = Auth::user();
        
        $currentBalance = (float) $this->payoutService->getPayoutOverview($user)['currentBalance'];
        $minWithdraw = (float) PlatformSetting::get('min_withdraw_amount', 10000);

        $request->validate([
            'amount_raw' => ['required', 'numeric', 'min:' . $minWithdraw, 'max:' . $currentBalance],
            'method' => 'required|string|in:Bank,DANA,ShopeePay',
            'account_detail' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
        ], [
            'amount_raw.min' => 'Jumlah penarikan minimal Rp ' . number_format($minWithdraw, 0, ',', '.') . '.',
            'amount_raw.max' => 'Jumlah penarikan melebihi saldo yang tersedia.',
            'method.required' => 'Metode penarikan wajib diisi.',
            'method.in' => 'Metode penarikan tidak valid.',
            'account_detail.required' => 'Detail akun/nomor telepon wajib diisi.',
            'account_detail.string' => 'Detail akun/nomor telepon harus berupa teks.',
            'account_detail.max' => 'Detail akun/nomor telepon terlalu panjang.',
            'account_name.required' => 'Nama akun wajib diisi.',
            'account_name.string' => 'Nama akun harus berupa teks.',
            'account_name.max' => 'Nama akun terlalu panjang.',
        ]);

        $result = $this->payoutService->processWithdrawal($user, $request->all());

        return redirect()->route('admin.payout.index')->with('success', $result['message']);
    }

    public function showPayoutHistory()
    {
        $user = Auth::user();
        $history = $this->payoutService->getPayoutHistory($user->id);
        
        return view('admin_seller.features.payouts.history', compact('history'));
    }
}
