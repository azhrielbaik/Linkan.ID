<?php

namespace App\Services\AdminSeller;

use App\Models\User;
use App\Models\UserPayoutDetail;
use Illuminate\Support\Facades\DB;
use App\Models\PlatformSetting;

class PayoutService
{
    /**
     * Get payout overview data for a user.
     *
     * @param User $user
     * @return array
     */
    public function getPayoutOverview(User $user): array
    {
        $myEarnings = (float)($user->balance ?? 0);

        $totalWithdrawn = (float)DB::table('payout_transactions')
            ->where('user_id', $user->id)
            ->sum('amount');
            
        $myEarnings = $myEarnings - $totalWithdrawn;

        // Sync balance if there's a discrepancy
        $currentBalance = (float)($user->balance ?? 0);
        if ($currentBalance != $myEarnings) {
            DB::table('users')->where('id', $user->id)->update(['balance' => $myEarnings]);
        }

        $totalEarnings = (float)DB::table('transactions')
            ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
            ->where('digital_products.user_id', $user->id)
            ->where('transactions.status', 'success')
            ->sum('transactions.total_price');

        $currentBalance = $totalEarnings - $totalWithdrawn;
        $payoutDetail = UserPayoutDetail::where('user_id', $user->id)->first();

        return [
            'totalEarnings' => $totalEarnings,
            'totalWithdrawn' => $totalWithdrawn,
            'currentBalance' => $currentBalance,
            'payoutDetail' => $payoutDetail
        ];
    }

    /**
     * Get withdraw form settings.
     *
     * @param User $user
     * @return array
     */
    public function getWithdrawFormSettings(User $user): array
    {
        return [
            'currentEarnings' => $user->balance ?? 0,
            'payoutDetail' => UserPayoutDetail::where('user_id', $user->id)->first(),
            'minWithdraw' => (float) PlatformSetting::get('min_withdraw_amount', 10000),
            'commissionPercent' => (float) PlatformSetting::get('platform_commission_percent', 5)
        ];
    }

    /**
     * Save payout method for a user.
     *
     * @param User $user
     * @param array $data
     * @return string Message
     */
    public function savePayoutMethod(User $user, array $data): string
    {
        $payoutDetail = UserPayoutDetail::where('user_id', $user->id)->first();
        $bankName = ($data['method_type'] === 'Bank') ? ($data['bank_name'] ?? null) : null;

        if ($payoutDetail) {
            $payoutDetail->update([
                'method_type' => $data['method_type'],
                'account_name' => $data['account_name'],
                'account_number' => $data['account_number'],
                'bank_name' => $bankName,
            ]);
            return 'Pengaturan metode pembayaran berhasil diperbarui!';
        }

        UserPayoutDetail::create([
            'user_id' => $user->id,
            'method_type' => $data['method_type'],
            'account_name' => $data['account_name'],
            'account_number' => $data['account_number'],
            'bank_name' => $bankName,
        ]);
        
        return 'Pengaturan metode pembayaran berhasil disimpan!';
    }

    /**
     * Process withdrawal request.
     *
     * @param User $user
     * @param array $data
     * @return array Message payload
     */
    public function processWithdrawal(User $user, array $data): array
    {
        $commissionPercent = (float) PlatformSetting::get('platform_commission_percent', 5);
        $amount = (float) $data['amount_raw'];
        $commission = $amount * ($commissionPercent / 100);
        $amountAfterCommission = $amount - $commission;

        DB::transaction(function() use ($user, $data, $amount, $commission, $amountAfterCommission) {
            DB::table('payout_transactions')->insert([
                'user_id' => $user->id,
                'amount' => $amountAfterCommission,
                'gross_amount' => $amount,
                'commission' => $commission,
                'method' => $data['method'],
                'account_name' => $data['account_name'],
                'account_number' => $data['account_detail'],
                'bank_name' => $data['bank_name'] ?? null,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            DB::table('users')->where('id', $user->id)->decrement('balance', $amount);

            \App\Services\ActivityLogger::log(
                'request_payout',
                "Seller {$user->name} mengajukan penarikan dana sebesar Rp " . number_format($amount, 0, ',', '.') . " via {$data['method']}.",
                ['amount' => $amount, 'net_amount' => $amountAfterCommission, 'method' => $data['method'], 'account_name' => $data['account_name']],
                $user->id
            );
        });

        return [
            'success' => true,
            'message' => 'Permintaan penarikan sebesar Rp ' . number_format($amount, 0, ',', '.') . ' berhasil diajukan melalui ' . $data['method'] . '. Menunggu verifikasi admin platform.'
        ];
    }

    /**
     * Get payout history.
     *
     * @param int $userId
     * @return \Illuminate\Support\Collection
     */
    public function getPayoutHistory(int $userId)
    {
        return DB::table('payout_transactions')
            ->where('user_id', $userId)
            ->latest()
            ->get();
    }
}
