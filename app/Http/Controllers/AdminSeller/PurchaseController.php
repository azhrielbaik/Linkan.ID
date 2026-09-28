<?php

namespace App\Http\Controllers\AdminSeller;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PurchaseController extends Controller
{
    /**
     * Display a listing of digital products purchased by the authenticated user.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $user = Auth::user();

        // Retrieve all transactions belonging to the user
        $purchases = Transaction::where('buyer_email', $user->email)
            ->with('product')
            ->latest()
            ->get();

        // Extract unique digital products purchased by the user
        $purchasedProducts = $purchases->pluck('product')
            ->filter()
            ->unique('id')
            ->values();

        return view('admin_seller.features.purchases.index', [
            'purchases' => $purchases,
            'purchasedProducts' => $purchasedProducts,
        ]);
    }

    /**
     * Backward-compatible alias for index.
     *
     * @return \Illuminate\View\View
     */
    public function myPurchase(): View
    {
        return $this->index();
    }
}
