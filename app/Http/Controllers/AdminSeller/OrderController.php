<?php

namespace App\Http\Controllers\AdminSeller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AdminSeller\OrderService;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        
        $filters = $request->only(['status', 'date', 'search']);
        $transactions = $this->orderService->getOrders($user->id, $filters);

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
                ]
            ]);
        }

        return view('admin_seller.features.orders.index', compact('transactions'));
    }

    public function getOrderDetail($id)
    {
        $user = Auth::user();
        $transaction = $this->orderService->getOrderDetail($user->id, $id);

        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        return response()->json($transaction);
    }

    public function updateTransactionStatus(Request $request, $id)
    {
        try {
            $this->orderService->updateOrderStatus($id, $request->status);
            return response()->json(['success' => true, 'message' => 'Transaction status updated']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
