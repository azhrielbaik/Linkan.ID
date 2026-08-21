<?php

namespace App\Http\Controllers\PlatformAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    /**
     * Menampilkan Log Aktivitas Admin.
     */
    public function activityLogs(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // Filter Aksi
        if ($action = $request->input('action')) {
            $query->where('action', $action);
        }

        // Filter Kategori Aksi
        if ($category = $request->input('category')) {
            if ($category === 'user') {
                $query->whereIn('action', ['suspend_user', 'activate_user']);
            } elseif ($category === 'product') {
                $query->whereIn('action', ['approve_product', 'reject_product']);
            } elseif ($category === 'payout') {
                $query->whereIn('action', ['approve_payout', 'reject_payout']);
            }
        }

        // Search Deskripsi / IP / Nama Admin
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Tanggal
        if ($startDate = $request->input('start_date')) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate = $request->input('end_date')) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $logs = $query->paginate(20)->withQueryString();

        // Stats
        $totalLogsCount = ActivityLog::count();
        $userActionCount = ActivityLog::whereIn('action', ['suspend_user', 'activate_user'])->count();
        $productActionCount = ActivityLog::whereIn('action', ['approve_product', 'reject_product'])->count();
        $payoutActionCount = ActivityLog::whereIn('action', ['approve_payout', 'reject_payout'])->count();

        return view('platformadmin.logs.activity', compact(
            'logs',
            'search',
            'action',
            'category',
            'startDate',
            'endDate',
            'totalLogsCount',
            'userActionCount',
            'productActionCount',
            'payoutActionCount'
        ));
    }

    /**
     * Menampilkan Log Transaksi Global seluruh platform.
     */
    public function transactionLogs(Request $request)
    {
        $query = Transaction::with(['product.user'])->latest();

        // Filter Status Tab
        $status = $request->input('status', 'all');
        if (in_array($status, ['success', 'pending', 'failed'])) {
            $query->where('status', $status);
        }

        // Search Order ID, Buyer, Product, Seller
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%")
                  ->orWhere('buyer_name', 'like', "%{$search}%")
                  ->orWhere('buyer_email', 'like', "%{$search}%")
                  ->orWhereHas('product', function ($pq) use ($search) {
                      $pq->where('title', 'like', "%{$search}%")
                         ->orWhereHas('user', function ($uq) use ($search) {
                             $uq->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                         });
                  });
            });
        }

        // Filter Tanggal
        if ($startDate = $request->input('start_date')) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate = $request->input('end_date')) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $transactions = $query->paginate(15)->withQueryString();

        // Statistik Ringkasan
        $totalVolume = Transaction::where('status', 'success')->sum('total_price');
        $totalSuccessCount = Transaction::where('status', 'success')->count();
        $totalPendingCount = Transaction::where('status', 'pending')->count();
        $totalFailedCount = Transaction::where('status', 'failed')->count();
        $totalTransactionsCount = Transaction::count();

        return view('platformadmin.logs.transactions', compact(
            'transactions',
            'status',
            'search',
            'startDate',
            'endDate',
            'totalVolume',
            'totalSuccessCount',
            'totalPendingCount',
            'totalFailedCount',
            'totalTransactionsCount'
        ));
    }
}
