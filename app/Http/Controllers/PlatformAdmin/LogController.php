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

        // Filter Aksi Spesifik
        if ($action = $request->input('action')) {
            $query->where('action', $action);
        }

        // Filter Kategori Aksi
        $category = $request->input('category', 'all');
        if ($category === 'admin') {
            $query->whereIn('action', [
                'suspend_user', 'activate_user',
                'approve_product', 'reject_product',
                'approve_payout', 'reject_payout',
                'update_platform_settings', 'create_broadcast', 'delete_broadcast'
            ]);
        } elseif ($category === 'auth') {
            $query->whereIn('action', [
                'user_register', 'user_login', 'user_logout',
                'password_reset_otp_sent', 'password_reset_success'
            ]);
        } elseif ($category === 'seller') {
            $query->whereIn('action', [
                'create_product', 'update_product', 'delete_product',
                'request_payout', 'create_shortlink', 'update_shortlink', 'update_account'
            ]);
        }

        // Search Deskripsi / IP / Nama / Email Pengguna
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('username', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Tanggal
        $startDate = $request->input('start_date') ?: $request->input('date', '');
        $endDate   = $request->input('end_date', '');
        if ($startDate && $endDate) {
            $query->whereDate('created_at', '>=', $startDate)
                  ->whereDate('created_at', '<=', $endDate);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $logs = $query->paginate(20)->withQueryString();

        // Stats
        $totalLogsCount   = ActivityLog::count();
        $adminActionCount = ActivityLog::whereIn('action', [
            'suspend_user', 'activate_user',
            'approve_product', 'reject_product',
            'approve_payout', 'reject_payout',
            'update_platform_settings', 'create_broadcast', 'delete_broadcast'
        ])->count();
        $authActionCount  = ActivityLog::whereIn('action', [
            'user_register', 'user_login', 'user_logout',
            'password_reset_otp_sent', 'password_reset_success'
        ])->count();
        $sellerActionCount = ActivityLog::whereIn('action', [
            'create_product', 'update_product', 'delete_product',
            'request_payout', 'create_shortlink', 'update_shortlink', 'update_account'
        ])->count();

        return view('platformadmin.logs.activity', compact(
            'logs',
            'category',
            'action',
            'search',
            'startDate',
            'endDate',
            'totalLogsCount',
            'adminActionCount',
            'authActionCount',
            'sellerActionCount'
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
        $startDate = $request->input('start_date') ?: $request->input('date', '');
        $endDate   = $request->input('end_date', '');
        if ($startDate && $endDate) {
            $query->whereDate('created_at', '>=', $startDate)
                  ->whereDate('created_at', '<=', $endDate);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
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

    /**
     * Endpoint autocomplete suggestion untuk Log Aktivitas Admin.
     */
    public function activitySuggest(Request $request)
    {
        $q = trim($request->query('q', ''));
        if (empty($q)) {
            return response()->json([]);
        }

        // Ambil data yang relevan dari deskripsi, action, IP, atau user
        $logs = ActivityLog::with('user')
            ->where(function ($query) use ($q) {
                $query->where('description', 'like', "%{$q}%")
                      ->orWhere('action', 'like', "%{$q}%")
                      ->orWhere('ip_address', 'like', "%{$q}%")
                      ->orWhereHas('user', function ($uq) use ($q) {
                          $uq->where('name', 'like', "%{$q}%")
                             ->orWhere('email', 'like', "%{$q}%")
                             ->orWhere('username', 'like', "%{$q}%");
                      });
            })
            ->latest()
            ->limit(10)
            ->get();

        $suggestions = collect();

        foreach ($logs as $log) {
            // Saran dari User
            if ($log->user) {
                if (stripos($log->user->name, $q) !== false) {
                    $suggestions->push([
                        'label' => $log->user->name . ' (User)',
                        'value' => $log->user->name
                    ]);
                }
                if (stripos($log->user->email, $q) !== false) {
                    $suggestions->push([
                        'label' => $log->user->email . ' (Email)',
                        'value' => $log->user->email
                    ]);
                }
            }

            // Saran dari Deskripsi
            if ($log->description && stripos($log->description, $q) !== false) {
                $descExcerpt = mb_strimwidth($log->description, 0, 50, '...');
                $suggestions->push([
                    'label' => $descExcerpt,
                    'value' => $log->description
                ]);
            }

            // Saran dari IP Address
            if ($log->ip_address && stripos($log->ip_address, $q) !== false) {
                $suggestions->push([
                    'label' => $log->ip_address . ' (IP Address)',
                    'value' => $log->ip_address
                ]);
            }

            // Saran dari Action
            if ($log->action && stripos($log->action, $q) !== false) {
                $suggestions->push([
                    'label' => $log->action . ' (Action)',
                    'value' => $log->action
                ]);
            }
        }

        // Ambil unique berdasarkan value dan batasi maksimal 5 item
        $uniqueSuggestions = $suggestions->unique('value')->values()->take(5);

        return response()->json($uniqueSuggestions);
    }

    /**
     * Endpoint autocomplete suggestion untuk Log Transaksi Global.
     */
    public function transactionSuggest(Request $request)
    {
        $q = trim($request->query('q', ''));
        if (empty($q)) {
            return response()->json([]);
        }

        $transactions = Transaction::with(['product.user'])
            ->where(function ($query) use ($q) {
                $query->where('order_id', 'like', "%{$q}%")
                      ->orWhere('buyer_name', 'like', "%{$q}%")
                      ->orWhere('buyer_email', 'like', "%{$q}%")
                      ->orWhereHas('product', function ($pq) use ($q) {
                          $pq->where('title', 'like', "%{$q}%")
                             ->orWhereHas('user', function ($uq) use ($q) {
                                 $uq->where('name', 'like', "%{$q}%")
                                    ->orWhere('email', 'like', "%{$q}%");
                             });
                      });
            })
            ->latest()
            ->limit(10)
            ->get();

        $suggestions = collect();

        foreach ($transactions as $tx) {
            // Order ID
            if ($tx->order_id && stripos($tx->order_id, $q) !== false) {
                $suggestions->push([
                    'label' => $tx->order_id . ' (Order ID)',
                    'value' => $tx->order_id
                ]);
            }

            // Buyer Name / Email
            if ($tx->buyer_name && stripos($tx->buyer_name, $q) !== false) {
                $suggestions->push([
                    'label' => $tx->buyer_name . ' (Pembeli)',
                    'value' => $tx->buyer_name
                ]);
            }
            if ($tx->buyer_email && stripos($tx->buyer_email, $q) !== false) {
                $suggestions->push([
                    'label' => $tx->buyer_email . ' (Email Pembeli)',
                    'value' => $tx->buyer_email
                ]);
            }

            // Product Title
            if ($tx->product && $tx->product->title && stripos($tx->product->title, $q) !== false) {
                $suggestions->push([
                    'label' => $tx->product->title . ' (Produk)',
                    'value' => $tx->product->title
                ]);
            }

            // Seller Name
            if ($tx->product && $tx->product->user && stripos($tx->product->user->name, $q) !== false) {
                $suggestions->push([
                    'label' => $tx->product->user->name . ' (Seller)',
                    'value' => $tx->product->user->name
                ]);
            }
        }

        $uniqueSuggestions = $suggestions->unique('value')->values()->take(5);

        return response()->json($uniqueSuggestions);
    }
}
