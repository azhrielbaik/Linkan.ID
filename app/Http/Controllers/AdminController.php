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

    public function myLinkan(Request $request)
    {
        $user = auth()->user();
        $digitalProducts = DigitalProduct::where('user_id', $user->id)->latest()->get();
        $appearance = \App\Models\Appearance::where('user_id', $user->id)->first();
        $shortlinks = \App\Models\Shortlink::where('user_id', $user->id)
            ->latest()
            ->paginate(6, ['*'], 'links_page');

        // Total page views for user's microsite
        $totalViews = \Illuminate\Support\Facades\DB::table('link_views')
            ->where('user_id', $user->id)
            ->count();

        $totalProducts = $digitalProducts->where('is_active', 1)->where('verification_status', 'approved')->count();
        $totalShortlinks = \App\Models\Shortlink::where('user_id', $user->id)->count();

        $viewMode = $request->query('mode', 'gallery'); // 'gallery' or 'edit'

        return view('homeadminS.mylinkan', compact(
            'digitalProducts',
            'appearance',
            'shortlinks',
            'totalViews',
            'totalProducts',
            'totalShortlinks',
            'viewMode'
        ));
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
