<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Assuming user authentication
use Illuminate\Support\Facades\DB;   // For database operations
use App\Models\DigitalProduct; // Diperlukan untuk join di perhitungan total earnings
use App\Models\UserPayoutDetail; // Import model UserPayoutDetail

class PayoutController extends Controller
{
    /**
     * Menampilkan halaman Payout Setting.
     */
    public function index()
    {
        $user = Auth::user();

        // Debug: Log user ID
        \Log::info('Payout - Calculating earnings for user ID: ' . $user->id);

        // Ambil semua transaksi untuk debugging
        $allTransactions = DB::table('transactions')
            ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
            ->where('digital_products.user_id', $user->id)
            ->select('transactions.*', 'digital_products.title')
            ->get();

        // Log semua transaksi (termasuk yang tidak success)
        \Log::info('Payout - All transactions for user:');
        foreach ($allTransactions as $transaction) {
            \Log::info("Transaction ID: {$transaction->id}, Status: {$transaction->status}, Amount: {$transaction->total_price}, Product: {$transaction->title}, Created: {$transaction->created_at}");
        }

        // Ambil semua transaksi yang berhasil
        $successTransactions = DB::table('transactions')
            ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
            ->where('digital_products.user_id', $user->id)
            ->where('transactions.status', 'success')
            ->select('transactions.*', 'digital_products.title')
            ->get();

        // Log transaksi yang berhasil
        \Log::info('Payout - Successful transactions:');
        foreach ($successTransactions as $transaction) {
            \Log::info("Success Transaction ID: {$transaction->id}, Amount: {$transaction->total_price}, Product: {$transaction->title}, Created: {$transaction->created_at}");
        }

        // Ambil saldo real time dari kolom 'balance' di tabel users
        $myEarnings = (float)(DB::table('users')->where('id', $user->id)->value('balance') ?? 0);

        // Kurangi total penarikan yang sudah dilakukan
        $totalWithdrawn = (float)DB::table('payout_transactions')
            ->where('user_id', $user->id)
            ->sum('amount');
        $myEarnings = $myEarnings - $totalWithdrawn;

        // Debug: Log hasil perhitungan
        \Log::info('Payout - Total Earnings Calculation: ' . $myEarnings);
        \Log::info('Payout - Raw SQL Query: ' . DB::table('transactions')
            ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
            ->where('digital_products.user_id', $user->id)
            ->where('transactions.status', 'success')
            ->toSql());
        \Log::info('Payout - Query Bindings: ' . json_encode([
            'user_id' => $user->id,
            'status' => 'success'
        ]));

        // Ambil data total penarikan dari database tanpa filter status
        $lastWithdraw = (float)(DB::table('payout_transactions')
            ->where('user_id', $user->id)
            ->sum('amount') ?? 0);

        // Ambil detail pembayaran user dari tabel user_payout_details
        $payoutDetail = UserPayoutDetail::where('user_id', $user->id)->first();

        // Update balance di tabel users jika berbeda dengan total pendapatan
        $currentBalance = (float)(DB::table('users')->where('id', $user->id)->value('balance') ?? 0);
        if ($currentBalance != $myEarnings) {
            \Log::info("Payout - Updating balance from {$currentBalance} to {$myEarnings}");
            DB::table('users')
                ->where('id', $user->id)
                ->update(['balance' => $myEarnings]);
        }

        // Debug: Log final values
        \Log::info('Payout - Final Values:');
        \Log::info('myEarnings: ' . $myEarnings);
        \Log::info('currentBalance: ' . $currentBalance);
        \Log::info('lastWithdraw: ' . $lastWithdraw);
        
        // Hitung total pendapatan (lifetime earnings)
        $totalEarnings = (float)DB::table('transactions')
            ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
            ->where('digital_products.user_id', $user->id)
            ->where('transactions.status', 'success')
            ->sum('transactions.total_price');

        // Hitung saldo bisa ditarik (currentBalance)
        $totalWithdrawn = (float)DB::table('payout_transactions')
            ->where('user_id', $user->id)
            ->sum('amount');
        $currentBalance = $totalEarnings - $totalWithdrawn;

        // Kirim ke view
        return view('homeadminS.payout', compact('totalEarnings', 'totalWithdrawn', 'currentBalance', 'payoutDetail'));
    }

    /**
     * Menampilkan form input jumlah penarikan.
     */
    public function showWithdrawForm()
    {
        $user = Auth::user();

        // Ambil saldo saat ini dari kolom 'balance' untuk validasi
        $currentEarnings = $user->balance ?? 0; // Menggunakan kolom 'balance' yang baru

        // Ambil detail pembayaran user yang sudah disimpan
        $payoutDetail = UserPayoutDetail::where('user_id', $user->id)->first();

        return view('homeadminS.withdraw_form', compact('currentEarnings', 'payoutDetail'));
    }

    /**
     * Menampilkan form untuk mengatur metode pembayaran.
     */
    public function showPayoutMethodForm()
    {
        $user = Auth::user();
        $payoutDetail = UserPayoutDetail::where('user_id', $user->id)->first();

        return view('homeadminS.payout_method_form', compact('payoutDetail'));
    }

    /**
     * Menyimpan atau memperbarui metode pembayaran user.
     */
    public function savePayoutMethod(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'method_type' => 'required|string|in:Bank,DANA,ShopeePay',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'bank_name' => 'nullable|string|max:255', // Nullable karena tidak semua metode punya bank_name
        ]);

        // Cek apakah user sudah punya detail pembayaran
        $payoutDetail = UserPayoutDetail::where('user_id', $user->id)->first();

        if ($payoutDetail) {
            // Update detail yang sudah ada
            $payoutDetail->update([
                'method_type' => $request->method_type,
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
                'bank_name' => ($request->method_type === 'Bank') ? $request->bank_name : null,
            ]);
            $message = 'Pengaturan metode pembayaran berhasil diperbarui!';
        } else {
            // Buat detail baru
            UserPayoutDetail::create([
                'user_id' => $user->id,
                'method_type' => $request->method_type,
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
                'bank_name' => ($request->method_type === 'Bank') ? $request->bank_name : null,
            ]);
            $message = 'Pengaturan metode pembayaran berhasil disimpan!';
        }

        return redirect()->route('admin.payout.index')->with('success', $message);
    }

    /**
     * Memproses permintaan penarikan dana.
     */
    public function processWithdrawal(Request $request)
    {
        $user = Auth::user();

        // Ambil saldo saat ini dari kolom 'balance' untuk validasi
        $currentEarnings = $user->balance ?? 0; // Menggunakan kolom 'balance' yang baru

        // Hitung saldo bisa ditarik (currentBalance)
        $totalEarnings = (float)DB::table('transactions')
            ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
            ->where('digital_products.user_id', $user->id)
            ->where('transactions.status', 'success')
            ->sum('transactions.total_price');
        $totalWithdrawn = (float)DB::table('payout_transactions')
            ->where('user_id', $user->id)
            ->sum('amount');
        $currentBalance = $totalEarnings - $totalWithdrawn;

        $request->validate([
            'amount_raw' => ['required', 'numeric', 'min:10000', 'max:' . $currentBalance],
            'method' => 'required|string|in:Bank,DANA,ShopeePay',
            'account_detail' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
        ], [
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

        $amount = $request->input('amount_raw');
        $method = $request->input('method');
        $accountDetail = $request->input('account_detail');
        $accountName = $request->input('account_name'); // Ambil account_name

        // --- Logika Bisnis Penarikan ---

        // Hitung komisi 5% untuk platform
        $commission = $amount * 0.05;
        $amountAfterCommission = $amount - $commission;
        $adminPlatformId = 1; // Ganti sesuai id admin platform Anda

        // 1. Catat transaksi penarikan di database (amount yang masuk ke seller adalah setelah dipotong komisi)
        DB::table('payout_transactions')->insert([
            'user_id' => $user->id,
            'amount' => $amountAfterCommission,
            'method' => $method,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // 2. Kurangi saldo pengguna yang dapat ditarik di database (langsung ke DB, total amount)
        \DB::table('users')->where('id', $user->id)->decrement('balance', $amount);

        // 3. Tambahkan komisi ke saldo admin platform
        \DB::table('users')->where('id', $adminPlatformId)->increment('balance', $commission);

        // 4. Catat ke tabel platform_commissions
        DB::table('platform_commissions')->insert([
            'seller_id' => $user->id,
            'platform_admin_id' => $adminPlatformId,
            'amount' => $amount,
            'commission' => $commission,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 5. Integrasi dengan Gateway Pembayaran Eksternal (Placeholder)
        //    Di sini Anda akan memanggil API dari penyedia pembayaran (misalnya, Midtrans, Xendit, atau API bank)
        //    untuk memulai transfer dana.
        //    Pastikan Anda memiliki detail bank/e-wallet pengguna yang tersimpan dengan aman.
        //    $payoutService->initiatePayout($amount, $user->bank_details);

        return redirect()->route('admin.payout.index')->with('success', 'Permintaan penarikan sebesar Rp ' . number_format($amount, 0, ',', '.') . ' berhasil diajukan melalui ' . $method . '. Status akan diperbarui segera.');
    }

    /**
     * Menampilkan riwayat penarikan dana.
     */
    public function showPayoutHistory()
    {
        $user = Auth::user();

        // Ambil data riwayat penarikan dari database untuk user yang login
        $history = DB::table('payout_transactions')
        ->select('user_id', 'amount', 'method')
        ->where('user_id', $user->id)
        ->latest()
        ->get();
        
        return view('homeadminS.payout_history', compact('history'));
    }
} 