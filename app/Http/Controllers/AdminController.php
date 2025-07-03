<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DigitalProduct;

class AdminController extends Controller
{
    public function beranda()
    {
        return view('homeadminS.beranda');
    }

    public function myLinkan()
    {
        $user = auth()->user();
        $digitalProducts = DigitalProduct::where('user_id', $user->id)->latest()->get();
        $appearance = \App\Models\Appearance::where('user_id', $user->id)->first();

        return view('homeadminS.mylinkan', compact('digitalProducts', 'appearance'));
    }

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
        return view('homeadminS.mypurchase', [
            'purchases' => $purchases,
            'purchasedProducts' => $purchasedProducts
        ]);
    }
}
