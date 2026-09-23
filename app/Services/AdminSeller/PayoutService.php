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
        // 1. Total pendapatan kotor dari produk digital yang terjual sukses
        $totalEarnings = (float) DB::table('transactions')
            ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
            ->where('digital_products.user_id', $user->id)
            ->where('transactions.status', 'success')
            ->sum('transactions.total_price');

        // 2. Total penarikan bersih yang berhasil diterima user (approved/completed)
        $totalWithdrawn = (float) DB::table('payout_transactions')
            ->where('user_id', $user->id)
            ->whereIn('status', ['approved', 'completed'])
            ->sum('amount');

        // 3. Total gross yang dipotong untuk payout yang sudah approved
        $approvedGross = (float) DB::table('payout_transactions')
            ->where('user_id', $user->id)
            ->whereIn('status', ['approved', 'completed'])
            ->sum(DB::raw('COALESCE(gross_amount, amount)'));

        // 4. Total gross yang sedang di-hold (status pending)
        $pendingGross = (float) DB::table('payout_transactions')
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->sum(DB::raw('COALESCE(gross_amount, amount)'));

        // 5. Total dana transaksi yang sedang dibekukan karena sengketa aktif (pending / under_review)
        $frozenDisputeAmount = (float) DB::table('disputes')
            ->where('seller_id', $user->id)
            ->whereIn('status', ['pending', 'under_review'])
            ->sum('amount');

        // 6. Saldo yang dapat ditarik: total pendapatan dikurangi penarikan approved, pending hold, dan sengketa tertahan
        // Catatan: Payout yang rejected TIDAK dihitung sebagai pengurang sehingga otomatis kembali ke saldo user
        $currentBalance = max(0, $totalEarnings - $approvedGross - $pendingGross - $frozenDisputeAmount);

        // Self-healing: sinkronkan nilai users.balance jika terdapat ketidaksesuaian
        $userBalance = (float) ($user->balance ?? 0);
        if ($userBalance != $currentBalance) {
            DB::table('users')->where('id', $user->id)->update(['balance' => $currentBalance]);
            $user->balance = $currentBalance;
        }

        $payoutDetail = UserPayoutDetail::where('user_id', $user->id)->first();

        return [
            'totalEarnings' => $totalEarnings,
            'totalWithdrawn' => $totalWithdrawn,
            'currentBalance' => $currentBalance,
            'frozenDisputeAmount' => $frozenDisputeAmount,
            'payoutDetail' => $payoutDetail,
            'history' => $this->getPayoutHistory($user->id)->take(10)
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
        $currentEarnings = (float) (DB::table('users')->where('id', $user->id)->value('balance') ?? 0);

        return [
            'currentEarnings' => $currentEarnings,
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
        if ((bool) PlatformSetting::get('freeze_payouts', 0)) {
            $msg = PlatformSetting::get('freeze_payouts_message')
                ?: 'Layanan penarikan dana sedang dibekukan sementara oleh sistem untuk audit atau pemeliharaan perbankan.';
            throw new \Exception($msg);
        }

        $commissionPercent = (float) PlatformSetting::get('platform_commission_percent', 5);
        $amount = (float) $data['amount_raw'];
        $commission = $amount * ($commissionPercent / 100);
        $amountAfterCommission = $amount - $commission;

        DB::transaction(function() use ($user, $data, $amount, $commission, $amountAfterCommission) {
            $freshUser = DB::table('users')->where('id', $user->id)->lockForUpdate()->first();
            $frozenDisputeAmount = (float) DB::table('disputes')
                ->where('seller_id', $user->id)
                ->whereIn('status', ['pending', 'under_review'])
                ->sum('amount');
            $withdrawableBalance = max(0, (float) ($freshUser->balance ?? 0) - $frozenDisputeAmount);

            if (!$freshUser || $withdrawableBalance < $amount) {
                $err = 'Saldo Anda tidak mencukupi untuk melakukan penarikan ini.';
                if ($frozenDisputeAmount > 0) {
                    $err .= ' (Terdapat dana tertahan sengketa sebesar Rp ' . number_format($frozenDisputeAmount, 0, ',', '.') . ')';
                }
                throw new \Exception($err);
            }

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
