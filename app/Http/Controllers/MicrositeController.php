<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DigitalProduct;
use App\Models\Appearance;
use App\Models\ImageElement;
use App\Models\DividerElement;
use App\Models\TextElement;
use App\Models\VideoElement;
use App\Models\SocialMediaElement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MicrositeController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $viewMode = $request->query('mode', 'gallery'); // 'gallery' or 'edit'

        $allDigitalProducts = DigitalProduct::where('user_id', $user->id)->latest()->get();
        $totalProducts = $allDigitalProducts->where('is_active', 1)->where('verification_status', 'approved')->count();

        // Mode Gallery: Tampilkan semua microsite
        if ($viewMode == 'gallery') {
            $digitalProducts = $allDigitalProducts;
            $appearances = Appearance::where('user_id', $user->id)->latest()->get();
            // Total page views per alias
            $viewsData = DB::table('link_views')
                ->select('link_id', DB::raw('count(*) as total'))
                ->where('user_id', $user->id)
                ->groupBy('link_id')
                ->pluck('total', 'link_id');

            return view('admin_seller.microsites.index', compact(
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
            return redirect()->route('admin.microsites.index', ['mode' => 'gallery'])->with('error', 'Pilih microsite yang ingin diedit.');
        }

        $appearance = Appearance::where('user_id', $user->id)->findOrFail($appearanceId);
        
        $blocksOrder = $appearance->blocks_order ? explode(',', $appearance->blocks_order) : [];
        $productIds = [];
        foreach ($blocksOrder as $block) {
            if (str_starts_with($block, 'digitalproduct_')) {
                $productIds[] = str_replace('digitalproduct_', '', $block);
            }
        }
        
        $digitalProducts = $allDigitalProducts->whereIn('id', $productIds);

        $imageElements = ImageElement::where('appearance_id', $appearance->id)->orderBy('order_position')->get();
        $dividerElements = DividerElement::where('appearance_id', $appearance->id)->orderBy('order_position')->get();
        $textElements = TextElement::where('appearance_id', $appearance->id)->orderBy('order_position')->get();
        $videoElements = VideoElement::where('appearance_id', $appearance->id)->orderBy('order_position')->get();
        $socialMediaElements = SocialMediaElement::where('appearance_id', $appearance->id)->get();

        return view('admin_seller.microsites.index', compact(
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

    public function store(Request $request)
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
        $appearance = new Appearance();
        $appearance->user_id = $user->id;
        $appearance->title = $request->title;
        $appearance->alias = strtolower($request->alias);
        $appearance->name = $user->name; // Default profile name
        $appearance->bio = $request->bio;
        $appearance->theme_color = $themeColor;
        $appearance->save();

        return redirect()->route('admin.microsites.index', ['mode' => 'edit', 'id' => $appearance->id])
            ->with('success', 'Microsite baru "' . $request->title . '" berhasil dibuat! Alamat Anda sekarang: linkan.id/' . $appearance->alias);
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $appearance = Appearance::where('user_id', $user->id)->findOrFail($id);
        
        $title = $appearance->title ?? $appearance->name;

        // Clean up digital products associated with this microsite
        $blocksOrder = $appearance->blocks_order ? explode(',', $appearance->blocks_order) : [];
        foreach ($blocksOrder as $block) {
            if (str_starts_with($block, 'digitalproduct_')) {
                $productId = str_replace('digitalproduct_', '', $block);
                $product = DigitalProduct::where('id', $productId)->where('user_id', $user->id)->first();
                if ($product) {
                    if ($product->media_files) {
                        $mediaFiles = is_string($product->media_files) ? json_decode($product->media_files, true) : $product->media_files;
                        if (is_array($mediaFiles)) {
                            foreach ($mediaFiles as $media) {
                                if (isset($media['path'])) Storage::disk('public')->delete($media['path']);
                            }
                        }
                    }
                    if ($product->deliverable_type === 'upload' && $product->deliverable_url) {
                        Storage::disk('public')->delete($product->deliverable_url);
                    }
                    $product->delete();
                }
            }
        }

        $appearance->delete();

        return redirect()->route('admin.microsites.index', ['mode' => 'gallery'])
            ->with('success', 'Microsite "' . $title . '" berhasil dihapus.');
    }
}
