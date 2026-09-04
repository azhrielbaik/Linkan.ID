<?php

namespace App\Http\Resources\PlatformAdmin;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SellerDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = $this->resource;

        // Fetch related stats
        $totalProducts    = $user->digitalProducts()->count();
        $activeProducts   = $user->digitalProducts()->where('is_active', true)->where('verification_status', 'approved')->count();
        $pendingProducts  = $user->digitalProducts()->where('verification_status', 'pending')->count();
        $rejectedProducts = $user->digitalProducts()->where('verification_status', 'rejected')->count();
        $takedownProducts = $user->digitalProducts()->where('is_active', false)->count();

        $totalViews  = DB::table('link_views')->where('link_id', $user->username)->count();
        $totalClicks = DB::table('link_clicks')->where('link_id', $user->username)->count();

        $totalTurnover = (float) DB::table('transactions')
            ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
            ->where('digital_products.user_id', $user->id)
            ->where('transactions.status', 'success')
            ->sum('transactions.total_price');

        $totalOrders = (int) DB::table('transactions')
            ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
            ->where('digital_products.user_id', $user->id)
            ->where('transactions.status', 'success')
            ->count();

        $totalWithdrawn = (float) DB::table('payout_transactions')
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            ->sum('amount');

        $pendingWithdraw = (float) DB::table('payout_transactions')
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->sum('amount');

        $currentBalance = (float) ($user->balance ?? 0);

        // Recent items
        $recentProducts = $user->digitalProducts()
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($p) {
                return [
                    'id'                  => $p->id,
                    'title'               => $p->title,
                    'price'               => (float) $p->price,
                    'sale_price'          => $p->sale_price ? (float) $p->sale_price : null,
                    'image'               => $p->image ? asset('storage/' . $p->image) : null,
                    'platform_type'       => $p->platform_type,
                    'verification_status' => $p->verification_status,
                    'is_active'           => (bool) $p->is_active,
                    'created_at'          => $p->created_at ? $p->created_at->format('d M Y') : '-',
                ];
            });

        $recentPayouts = DB::table('payout_transactions')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $appealsHistory = \App\Models\SuspensionAppeal::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($a, $index) {
                return [
                    'id'            => $a->id,
                    'attempt'       => $index + 1,
                    'appeal_reason' => $a->appeal_reason,
                    'status'        => $a->status,
                    'admin_notes'   => $a->admin_notes,
                    'submitted_at'  => $a->created_at->format('d M Y, H:i'),
                    'resolved_at'   => $a->resolved_at ? $a->resolved_at->format('d M Y, H:i') : null,
                ];
            })
            ->values();

        $avatarUrl = null;
        if ($user->avatar) {
            $avatarUrl = Str::startsWith($user->avatar, ['http://', 'https://'])
                ? $user->avatar
                : asset('storage/' . $user->avatar);
        }

        return [
            'status' => 'success',
            'user' => [
                'id'           => $user->id,
                'name'         => $user->name,
                'email'        => class_exists('\App\Models\ActivityLog') && method_exists('\App\Models\ActivityLog', 'maskEmail') 
                                  ? \App\Models\ActivityLog::maskEmail($user->email) 
                                  : $user->email,
                'username'     => $user->username,
                'role'         => $user->role,
                'is_suspended' => $user->isSuspended(),
                'avatar'       => $avatarUrl,
                'joined_at'    => $user->created_at ? $user->created_at->format('d M Y, H:i') : '-',
                'microsite_url'=> url('/linkan.id/' . ($user->username ?? $user->id)),
            ],
            'stats' => [
                'total_products'    => $totalProducts,
                'active_products'   => $activeProducts,
                'pending_products'  => $pendingProducts,
                'rejected_products' => $rejectedProducts,
                'takedown_products' => $takedownProducts,
                'total_views'       => $totalViews,
                'total_clicks'      => $totalClicks,
                'total_turnover'    => $totalTurnover,
                'total_orders'      => $totalOrders,
                'total_withdrawn'   => $totalWithdrawn,
                'pending_withdraw'  => $pendingWithdraw,
                'current_balance'   => $currentBalance,
            ],
            'recent_products'  => $recentProducts,
            'recent_payouts'   => $recentPayouts,
            'appeals_history'  => $appealsHistory,
        ];
    }
}
