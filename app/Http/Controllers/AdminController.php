<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function myPurchase()
    {
        $user = auth()->user();
        // Ambil semua transaksi user
        $purchases = \App\Models\Transaction::where('buyer_email', $user->email)
            ->with('product')
            ->latest()
            ->get();
        // Ambil produk digital unik yang sudah dibeli user
        $purchasedProducts = $purchases->pluck('product')->unique('id')->values();
        return view('admin_seller.features.purchases.index', [
            'purchases' => $purchases,
            'purchasedProducts' => $purchasedProducts
        ]);
    }
}
