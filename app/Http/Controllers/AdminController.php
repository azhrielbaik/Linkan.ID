<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DigitalProduct;

class AdminController extends Controller
{

    public function myLinkan(Request $request)
    {
        $user = auth()->user();
        $viewMode = $request->query('mode', 'gallery'); // 'gallery' or 'edit'

        $digitalProducts = DigitalProduct::where('user_id', $user->id)->latest()->get();
        $totalProducts = $digitalProducts->where('is_active', 1)->where('verification_status', 'approved')->count();

        // Mode Gallery: Tampilkan semua microsite
        if ($viewMode == 'gallery') {
            $appearances = \App\Models\Appearance::where('user_id', $user->id)->latest()->get();
            // Total page views per alias
            $viewsData = \Illuminate\Support\Facades\DB::table('link_views')
                ->select('link_id', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                ->where('user_id', $user->id)
                ->groupBy('link_id')
                ->pluck('total', 'link_id');

            return view('admin_seller.features.mylinkan.index', compact(
                'digitalProducts',
                'appearances',
                'viewsData',
                'totalProducts',
                'viewMode'
            ));
        }

        // Mode Edit: Harus ada ID microsite
        $appearanceId = $request->query('id');
        if (!$appearanceId) {
            return redirect()->route('admin.mylinkan', ['mode' => 'gallery'])->with('error', 'Pilih microsite yang ingin diedit.');
        }

        $appearance = \App\Models\Appearance::where('user_id', $user->id)->findOrFail($appearanceId);
        
        $imageElements = \App\Models\ImageElement::where('appearance_id', $appearance->id)->orderBy('order_position')->get();
        $dividerElements = \App\Models\DividerElement::where('appearance_id', $appearance->id)->orderBy('order_position')->get();
        $textElements = \App\Models\TextElement::where('appearance_id', $appearance->id)->orderBy('order_position')->get();
        $videoElements = \App\Models\VideoElement::where('appearance_id', $appearance->id)->orderBy('order_position')->get();
        $socialMediaElements = \App\Models\SocialMediaElement::where('appearance_id', $appearance->id)->get();

        return view('admin_seller.features.mylinkan.index', compact(
            'digitalProducts',
            'appearance',
            'imageElements',
            'dividerElements',
            'textElements',
            'videoElements',
            'socialMediaElements',
            'totalProducts',
            'viewMode'
        ));
    }

    public function storeMicrosite(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'title' => 'required|string|max:255',
            'alias' => 'required|string|max:12|alpha_dash|unique:appearances,alias',
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

        // Selalu buat microsite baru
        $appearance = new \App\Models\Appearance();
        $appearance->user_id = $user->id;
        $appearance->title = $request->title;
        $appearance->alias = strtolower($request->alias);
        $appearance->name = $user->name; // Default profile name
        $appearance->bio = $request->bio;
        $appearance->theme_color = $themeColor;
        $appearance->save();

        return redirect()->route('admin.mylinkan', ['mode' => 'edit', 'id' => $appearance->id])
            ->with('success', 'Microsite baru "' . $request->title . '" berhasil dibuat! Alamat Anda sekarang: linkan.id/' . $appearance->alias);
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
        return view('admin_seller.features.purchases.index', [
            'purchases' => $purchases,
            'purchasedProducts' => $purchasedProducts
        ]);
    }
}
