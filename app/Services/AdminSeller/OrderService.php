<?php

namespace App\Services\AdminSeller;

use App\Models\Transaction;
use App\Models\DigitalProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    /**
     * Get paginated orders for a seller.
     *
     * @param int $userId
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getOrders(int $userId, array $filters = [], int $perPage = 5)
    {
        $query = Transaction::with(['product'])
            ->whereHas('product', function($query) use ($userId) {
                $query->where('user_id', $userId);
            });

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['date'])) {
            $query->whereDate('created_at', $filters['date']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->whereHas('product', function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                })
                ->orWhere('buyer_name', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Get specific order details.
     *
     * @param int $userId
     * @param int $orderId
     * @return Transaction|null
     */
    public function getOrderDetail(int $userId, int $orderId): ?Transaction
    {
        return Transaction::with(['product'])
            ->whereHas('product', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where('id', $orderId)
            ->first();
    }

    /**
     * Update order status and manage seller balance.
     *
     * @param int $orderId
     * @param string $newStatus
     * @return bool
     * @throws \Exception
     */
    public function updateOrderStatus(int $orderId, string $newStatus): bool
    {
        $transaction = Transaction::findOrFail($orderId);
        $oldStatus = $transaction->status;

        if (!in_array($newStatus, Transaction::getValidStatuses())) {
            throw new \Exception('Invalid status');
        }

        $transaction->status = $newStatus;
        $transaction->save();

        if ($oldStatus !== 'success' && $newStatus === 'success') {
            $seller = $transaction->product->user;
            DB::table('users')
                ->where('id', $seller->id)
                ->increment('balance', $transaction->total_price);
        } else if ($oldStatus === 'success' && $newStatus !== 'success') {
            $seller = $transaction->product->user;
            DB::table('users')
                ->where('id', $seller->id)
                ->decrement('balance', $transaction->total_price);
        }

        return true;
    }
}
