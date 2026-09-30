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
        return view('admin_seller.features.digital-products.form');
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
        
        return view('admin_seller.features.digital-products.form', compact('product'));
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

        return view('admin_seller.features.digital-products.show',
            compact('product', 'user', 'reviews', 'avgRating', 'reviewCount'));
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
