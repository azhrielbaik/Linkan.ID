<?php

namespace App\Http\Controllers\AdminSeller;

use App\Http\Controllers\Controller;
use App\Models\PlatformSetting;
use App\Services\AdminSeller\PayoutService;
use Illuminate\Support\Facades\Auth;

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
        $data['isPayoutFrozen'] = (bool) PlatformSetting::get('freeze_payouts', 0);
        $data['freezeMessage'] = PlatformSetting::get('freeze_payouts_message') ?: 'Layanan penarikan dana (payout) sedang ditangguhkan sementara oleh sistem untuk audit atau pemeliharaan jaringan perbankan. Anda tetap dapat mengumpulkan saldo dari penjualan produk.';

        return view('admin_seller.features.payouts.index', $data);
    }

    public function showWithdrawForm()
    {
        if ((bool) PlatformSetting::get('freeze_payouts', 0)) {
            $msg = PlatformSetting::get('freeze_payouts_message')
                ?: 'Layanan penarikan dana (payout) sedang ditangguhkan sementara untuk audit sistem atau pemeliharaan jaringan perbankan.';

            return redirect()->route('admin.payout.index')->with('error', $msg);
        }

        $user = Auth::user();
        $data = $this->payoutService->getWithdrawFormSettings($user);

        return view('admin_seller.features.payouts.withdraw', $data);
    }

    public function showPayoutMethodForm()
    {
        $user = Auth::user();
        $data = $this->payoutService->getWithdrawFormSettings($user);

        return view('admin_seller.features.payouts.method', [
            'payoutDetail' => $data['payoutDetail'],
        ]);
    }

    public function savePayoutMethod(\App\Http\Requests\AdminSeller\SavePayoutMethodRequest $request)
    {
        $user = Auth::user();

        $message = $this->payoutService->savePayoutMethod($user, $request->validated());

        return redirect()->route('admin.payout.index')->with('success', $message);
    }

    public function processWithdrawal(\App\Http\Requests\AdminSeller\PayoutWithdrawRequest $request)
    {
        if ((bool) PlatformSetting::get('freeze_payouts', 0)) {
            $msg = PlatformSetting::get('freeze_payouts_message')
                ?: 'Layanan penarikan dana (payout) sedang ditangguhkan sementara untuk audit sistem atau pemeliharaan jaringan perbankan.';

            return redirect()->route('admin.payout.index')->with('error', $msg);
        }

        $user = Auth::user();

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
