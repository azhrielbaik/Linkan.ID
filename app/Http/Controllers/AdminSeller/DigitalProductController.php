<?php

namespace App\Http\Controllers\AdminSeller;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDigitalProductRequest;
use App\Http\Requests\UpdateDigitalProductRequest;
use App\Services\AdminSeller\DigitalProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DigitalProductController extends Controller
{
    protected $digitalProductService;

    public function __construct(DigitalProductService $digitalProductService)
    {
        $this->digitalProductService = $digitalProductService;
    }

    public function index()
    {
        $user = Auth::user();
        
        $products = \App\Models\DigitalProduct::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        $totalSales = (float)\Illuminate\Support\Facades\DB::table('transactions')
            ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
            ->where('digital_products.user_id', $user->id)
            ->where('transactions.status', 'success')
            ->sum('transactions.total_price');

        $totalOrders = \Illuminate\Support\Facades\DB::table('transactions')
            ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
            ->where('digital_products.user_id', $user->id)
            ->where('transactions.status', 'success')
            ->count();

        return view('admin_seller.features.digital-products.index', compact(
            'products', 'totalSales', 'totalOrders'
        ));
    }

    public function create()
    {
        $cleanDescription = old('description', '');
        if (!empty($cleanDescription)) {
            $cleanDescription = preg_replace('/\s*style\s*=\s*(["\']).*?\1/i', '', $cleanDescription);
            $cleanDescription = preg_replace('/<\/?span[^>]*>/i', '', $cleanDescription);
            $cleanDescription = preg_replace('/<\/?font[^>]*>/i', '', $cleanDescription);
        }
        $existingPhotos = [];
        $hasInitialPlatformFile = false;

        return view('admin_seller.features.digital-products.form', compact(
            'cleanDescription',
            'existingPhotos',
            'hasInitialPlatformFile'
        ));
    }

    public function store(StoreDigitalProductRequest $request)
    {
        $data = $request->only([
            'title', 'description', 'platform_type', 'platform_url',
            'button_text'
        ]);

        $data['price'] = $request->price_raw;
        $data['sale_price'] = $request->filled('sale_price_raw') ? $request->sale_price_raw : null;
        $data['has_quantity_limit'] = $request->has('has_quantity_limit');
        $data['quantity'] = $request->has('has_quantity_limit') ? $request->quantity : null;

        $photos = $request->file('photos') ?? $request->file('image');

        $this->digitalProductService->storeProduct(
            $data,
            Auth::id(),
            $photos,
            $request->file('platform_file'),
            $request->input('existing_media')
        );

        return redirect()->route('admin.digital-products.index')->with('success', 'Produk digital berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $product = $this->digitalProductService->getProduct($id, Auth::id());
        
        $cleanDescription = old('description');
        if ($cleanDescription !== null) {
            $cleanDescription = preg_replace('/\s*style\s*=\s*(["\']).*?\1/i', '', $cleanDescription);
            $cleanDescription = preg_replace('/<\/?span[^>]*>/i', '', $cleanDescription);
            $cleanDescription = preg_replace('/<\/?font[^>]*>/i', '', $cleanDescription);
        } else {
            $cleanDescription = $product->clean_description ?? '';
        }

        $existingPhotos = $product->existing_photos_list ?? [];
        $oldExistingMedia = old('existing_media');
        if ($oldExistingMedia) {
            $decoded = json_decode($oldExistingMedia, true);
            if (is_array($decoded)) {
                $existingPhotos = $decoded;
            }
        }

        $hasInitialPlatformFile = $product->has_platform_file ?? false;

        return view('admin_seller.features.digital-products.form', compact(
            'product',
            'cleanDescription',
            'existingPhotos',
            'hasInitialPlatformFile'
        ));
    }
    
    
    public function show($id)
    {
        $user    = Auth::user();
        $product = \App\Models\DigitalProduct::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        // Ambil review terbaru, hanya yang is_visible = true
        $reviews = \App\Models\ProductReview::where('product_id', $id)
            ->where('is_visible', true)
            ->orderBy('created_at', 'desc')
            ->get();

        // Hitung rata-rata rating
        $avgRating   = $reviews->avg('rating') ?? 0;
        $reviewCount = $reviews->count();

        // Siapkan data gambar
        $images = [];
        $mediaFiles = is_string($product->media_files) ? json_decode($product->media_files, true) : $product->media_files;
        if (is_array($mediaFiles) && count($mediaFiles) > 0) {
            foreach ($mediaFiles as $media) {
                if (is_array($media)) {
                    if (isset($media['url']) || isset($media['path'])) {
                        $images[] = $media['url'] ?? $media['path'];
                    }
                } elseif (is_string($media)) {
                    $images[] = $media;
                }
            }
        }
        if (empty($images) && $product->image) {
            $images[] = $product->image;
        }

        $mainImage = count($images) > 0 ? resolveProductImageUrl($images[0]) : 'https://via.placeholder.com/600x600?text=No+Image';

        // Siapkan data harga
        $currentPrice = $product->sale_price ?: $product->price;
        $originalPrice = $product->sale_price ? $product->price : ($product->price * 1.2);

        // Siapkan statistik rating per bintang (5 ke 1)
        $ratingDistribution = [];
        for ($star = 5; $star >= 1; $star--) {
            $count = $reviews->where('rating', $star)->count();
            $ratingDistribution[$star] = [
                'count' => $count,
                'percent' => $reviewCount > 0 ? round(($count / $reviewCount) * 100) : 0,
            ];
        }

        return view('admin_seller.features.digital-products.show', compact(
            'product',
            'user',
            'reviews',
            'avgRating',
            'reviewCount',
            'images',
            'mainImage',
            'currentPrice',
            'originalPrice',
            'ratingDistribution'
        ));
    }
    public function update(UpdateDigitalProductRequest $request, $id)
    {
        $product = $this->digitalProductService->getProduct($id, Auth::id());
        
        $data = $request->only([
            'title', 'description', 'platform_type', 'platform_url',
            'button_text'
        ]);

        $data['price'] = $request->price_raw;
        $data['sale_price'] = $request->filled('sale_price_raw') ? $request->sale_price_raw : null;
        $data['has_quantity_limit'] = $request->has('has_quantity_limit');
        $data['quantity'] = $request->has('has_quantity_limit') ? $request->quantity : null;

        $photos = $request->file('photos') ?? $request->file('image');

        $this->digitalProductService->updateProduct(
            $product,
            $data,
            $photos,
            $request->file('platform_file'),
            $request->input('existing_media')
        );

        return redirect()->route('admin.digital-products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $product = $this->digitalProductService->getProduct($id, $user->id);

        // Hapus dari blocks_order semua appearances milik user ini
        $appearances = \App\Models\Appearance::where('user_id', $user->id)->get();
        $key = 'digitalproduct_' . $product->id;
        foreach ($appearances as $appearance) {
            if ($appearance->blocks_order && str_contains($appearance->blocks_order, $key)) {
                $order = array_filter(
                    explode(',', $appearance->blocks_order),
                    fn($b) => $b !== $key
                );
                $appearance->blocks_order = implode(',', array_values($order));
                $appearance->save();
            }
        }

        $msg = $this->digitalProductService->deleteProduct($product);

        return redirect()->back()->with('success', $msg);
    }
}
