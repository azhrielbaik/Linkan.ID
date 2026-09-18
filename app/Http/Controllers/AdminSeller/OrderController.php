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

        return view('admin_seller.features.orders.index', compact('transactions'));
    }

    public function getOrderDetail($id)
    {
        $user = Auth::user();
        $order = $this->orderService->getOrderDetail($user->id, $id);

        if (!$order) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        return view('admin_seller.features.orders.partials._detail', compact('order'));
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
