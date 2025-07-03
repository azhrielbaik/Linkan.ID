<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\DigitalProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Debug: Cek user yang sedang login
        \Log::info('User ID: ' . $user->id);

        $query = Transaction::with(['product'])
            ->whereHas('product', function($query) use ($user) {
                $query->where('user_id', $user->id);
            });

        // Filter berdasarkan status jika ada
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tanggal jika ada
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Filter berdasarkan pencarian jika ada
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('product', function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                })
                ->orWhere('buyer_name', 'like', "%{$search}%");
            });
        }

        // Debug: Log query yang dijalankan
        \Log::info('SQL Query: ' . $query->toSql());
        \Log::info('Query Bindings: ' . json_encode($query->getBindings()));

        $transactions = $query->orderBy('created_at', 'desc')->paginate(5);

        // Debug: Log jumlah transaksi yang ditemukan
        \Log::info('Number of transactions found: ' . $transactions->count());

        if ($request->ajax()) {
            return response()->json([
                'transactions' => $transactions->items(),
                'pagination' => [
                    'current_page' => $transactions->currentPage(),
                    'last_page' => $transactions->lastPage(),
                    'per_page' => $transactions->perPage(),
                    'total' => $transactions->total(),
                    'has_more_pages' => $transactions->hasMorePages(),
                    'next_page_url' => $transactions->nextPageUrl(),
                    'prev_page_url' => $transactions->previousPageUrl(),
                ],
                'debug' => [
                    'user_id' => $user->id,
                    'query' => $query->toSql(),
                    'bindings' => $query->getBindings(),
                    'count' => $transactions->count(),
                    'filters' => [
                        'status' => $request->status,
                        'date' => $request->date,
                        'search' => $request->search
                    ]
                ]
            ]);
        }

        return view('homeadminS.orders', [
            'transactions' => $transactions
        ]);
    }

    public function getOrderDetail($id)
    {
        $user = Auth::user();
        $transaction = Transaction::with(['product'])
            ->whereHas('product', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('id', $id)
            ->first();

        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        return response()->json($transaction);
    }

    public function updateTransactionStatus(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $oldStatus = $transaction->status;
        $newStatus = $request->status;

        // Debug: Log perubahan status
        \Log::info("Order - Updating transaction status:");
        \Log::info("Transaction ID: {$transaction->id}");
        \Log::info("Old Status: {$oldStatus}");
        \Log::info("New Status: {$newStatus}");
        \Log::info("Amount: {$transaction->total_price}");

        // Validasi status
        if (!in_array($newStatus, Transaction::getValidStatuses())) {
            return response()->json(['error' => 'Invalid status'], 400);
        }

        // Update status transaksi
        $transaction->status = $newStatus;
        $transaction->save();

        // Jika status berubah menjadi success, update balance seller
        if ($oldStatus !== 'success' && $newStatus === 'success') {
            $product = $transaction->product;
            $seller = $product->user;
            
            // Debug: Log update balance
            \Log::info("Order - Updating balance for seller:");
            \Log::info("Seller ID: {$seller->id}");
            \Log::info("Amount to add: {$transaction->total_price}");
            
            // Update balance seller
            DB::table('users')
                ->where('id', $seller->id)
                ->increment('balance', $transaction->total_price);
        }
        // Jika status berubah dari success ke status lain, kurangi balance seller
        else if ($oldStatus === 'success' && $newStatus !== 'success') {
            $product = $transaction->product;
            $seller = $product->user;
            
            // Debug: Log update balance
            \Log::info("Order - Reducing balance for seller:");
            \Log::info("Seller ID: {$seller->id}");
            \Log::info("Amount to reduce: {$transaction->total_price}");
            
            // Kurangi balance seller
            DB::table('users')
                ->where('id', $seller->id)
                ->decrement('balance', $transaction->total_price);
        }

        return response()->json(['success' => true, 'message' => 'Transaction status updated']);
    }
} 