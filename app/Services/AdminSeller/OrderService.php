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
     * @return \Illuminate\Contracts\Pagination\CursorPaginator
     */
    /**
     * Get paginated orders for a seller.
     *
     * @param int $userId
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\CursorPaginator
     */
    public function getOrders(int $userId, array $filters = [], int $perPage = 10)
    {
        // Auto-cancel pending orders older than 24 hours for this seller
        $this->autoCancelExpiredPendingOrders($userId, 24);

        $query = Transaction::with(['product'])
            ->whereHas('product', function($query) use ($userId) {
                $query->where('user_id', $userId);
            });

        if (!empty($filters['status'])) {
            $status = strtolower($filters['status']);
            if (in_array($status, ['success', 'completed', 'complete'])) {
                $status = 'success';
            } elseif (in_array($status, ['failed', 'cancelled', 'cancel'])) {
                $status = 'failed';
            }
            $query->where('status', $status);
        }

        if (!empty($filters['date'])) {
            $query->whereDate('created_at', $filters['date']);
        } elseif (empty($filters['search'])) {
            // Default to last 30 days if no date filter and no search is applied
            $query->where('created_at', '>=', now()->subDays(30));
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

        return $query->orderBy('created_at', 'desc')->cursorPaginate($perPage);
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
        $order = Transaction::with(['product'])
            ->whereHas('product', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where('id', $orderId)
            ->first();

        if ($order && $order->status_value === 'pending' && $order->created_at && $order->created_at->lt(now()->subHours(24))) {
            $order->status = \App\Enums\TransactionStatus::FAILED;
            $order->save();
        }

        return $order;
    }

    /**
     * Auto-cancel pending orders that are older than the specified hours (default 24 hours).
     *
     * @param int|null $userId If specified, only cancels for this seller; otherwise globally.
     * @param int $hours Expiration threshold in hours (default 24).
     * @return int Number of cancelled orders.
     */
    public function autoCancelExpiredPendingOrders(?int $userId = null, int $hours = 24): int
    {
        $cutoff = now()->subHours($hours);

        $query = Transaction::where('status', 'pending')
            ->where('created_at', '<', $cutoff);

        if ($userId !== null) {
            $query->whereHas('product', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            });
        }

        $expiredOrders = $query->get();
        $count = 0;

        foreach ($expiredOrders as $order) {
            $order->status = \App\Enums\TransactionStatus::FAILED;
            $order->save();
            $count++;
        }

        if ($count > 0) {
            Log::info("Auto-cancelled {$count} pending orders older than {$hours} hours.");
        }

        return $count;
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
        $oldStatus = $transaction->status instanceof \BackedEnum ? $transaction->status->value : (string) $transaction->status;

        $normalizedStatus = strtolower($newStatus);
        if (in_array($normalizedStatus, ['cancelled', 'cancel', 'failed'])) {
            $normalizedStatus = 'failed';
        } elseif (in_array($normalizedStatus, ['success', 'completed', 'complete'])) {
            $normalizedStatus = 'success';
        }

        if (!in_array($normalizedStatus, Transaction::getValidStatuses())) {
            throw new \Exception('Invalid status');
        }

        $transaction->status = $normalizedStatus;
        $transaction->save();

        if ($oldStatus !== 'success' && $normalizedStatus === 'success') {
            $seller = $transaction->product->user;
            DB::table('users')
                ->where('id', $seller->id)
                ->increment('balance', $transaction->total_price);
        } else if ($oldStatus === 'success' && $normalizedStatus !== 'success') {
            $seller = $transaction->product->user;
            DB::table('users')
                ->where('id', $seller->id)
                ->decrement('balance', $transaction->total_price);
        }

        return true;
    }
}
