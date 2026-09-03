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
        // Route ini biasanya tidak digunakan karena tabel product ada di dashboard atau halaman tersendiri
        // Jika ada halaman khusus index product, bisa render view di sini
        return redirect()->route('mylinkan');
    }

    public function create()
    {
        return view('admin_seller.features.digital_products.form');
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

        $this->digitalProductService->storeProduct(
            $data,
            Auth::id(),
            $request->file('image'),
            $request->file('platform_file')
        );

        return redirect()->route('mylinkan')->with('success', 'Digital product added successfully!');
    }

    public function edit($id)
    {
        $product = $this->digitalProductService->getProduct($id, Auth::id());
        
        return view('admin_seller.features.digital_products.form', compact('product'));
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

        $this->digitalProductService->updateProduct(
            $product,
            $data,
            $request->file('image'),
            $request->file('platform_file')
        );

        return redirect()->route('mylinkan')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $product = $this->digitalProductService->getProduct($id, Auth::id());
        $msg = $this->digitalProductService->deleteProduct($product);

        return redirect()->back()->with('success', $msg);
    }
}
