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
        $imageElements = \App\Models\ImageElement::where('user_id', $user->id)->get();
        $dividerElements = \App\Models\DividerElement::where('user_id', $user->id)->get();
        $textElements = \App\Models\TextElement::where('user_id', $user->id)->get();
        $videoElements = \App\Models\VideoElement::where('user_id', $user->id)->get();
        $socialMediaElements = \App\Models\SocialMediaElement::where('user_id', $user->id)->get();

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
            'imageElements',
            'dividerElements',
            'textElements',
            'videoElements',
            'socialMediaElements',
            'totalViews',
            'totalProducts',
            'totalShortlinks',
            'viewMode'
        ));
    }

    public function storeMicrosite(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'purpose' => 'required|string|in:portofolio,marketing,affiliate,lainnya',
            'bio' => 'nullable|string|max:1000',
        ]);

        $themeColors = [
            'portofolio' => '#2563EB',
            'marketing'  => '#FF9040',
            'affiliate'  => '#059669',
            'lainnya'    => '#7C3AED',
        ];

        $themeColor = $themeColors[$request->purpose] ?? '#FF9040';

        $appearance = \App\Models\Appearance::where('user_id', $user->id)->first();
        if (!$appearance) {
            $appearance = new \App\Models\Appearance();
            $appearance->user_id = $user->id;
        }

        $appearance->name = $request->name;
        $appearance->bio = $request->bio;
        $appearance->theme_color = $themeColor;
        $appearance->save();

        return redirect()->route('admin.mylinkan', ['mode' => 'edit'])
            ->with('success', 'Microsite baru "' . $request->name . '" berhasil dibuat! Silakan kustomisasi blok & konten Anda.');
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
