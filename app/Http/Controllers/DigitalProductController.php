<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDigitalProductRequest;
use App\Http\Requests\UpdateDigitalProductRequest;
use App\Mail\SendDigitalProductMail;
use App\Models\DigitalProduct;
use App\Models\Transaction;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Midtrans\Snap;

use App\Services\CheckoutService;

class DigitalProductController extends Controller
{
    protected $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    public function show($id)
{
    $product = DigitalProduct::findOrFail($id);
    $user = $product->user; // relasi user() di model DigitalProduct

    if ($product->is_active === false || $product->is_active === 0) {
        abort(403, 'Produk ini tidak tersedia karena telah dinonaktifkan oleh Admin.');
    }

    if ($user && $user->isSuspended()) {
        abort(403, 'Produk ini tidak tersedia karena akun penjual sedang ditangguhkan.');
    }

     // Reset qty jadi 1 setiap buka halaman
    session(["cart.qty.$id" => 1]);

    return view('public.product-detail', compact('product', 'user'));
}


    public function updateQty(Request $request)
{
    $request->validate([
        'product_id' => 'required|integer',
        'qty' => 'required|integer|min:1',
        'price' => 'nullable|numeric|min:0'
    ]);

    session()->put("cart.qty.{$request->product_id}", $request->qty);
    if ($request->has('price')) {
        session()->put("cart.price.{$request->product_id}", $request->price);
    }

    return response()->json(['status' => 'success']);
}

public function checkoutSuccess(Request $request, $id)
{
    $product = DigitalProduct::findOrFail($id);
    
    // Ambil data transaksi terakhir dari sesi atau DB (opsional)
    // Untuk saat ini kita passing product dan order_id jika ada
    $orderId = $request->query('order_id');
    $transaction = null;
    if ($orderId) {
        $transaction = \App\Models\Transaction::where('order_id', $orderId)->first();
    }
    // Determine the microsite alias
    $appearances = \App\Models\Appearance::where('user_id', $product->user_id)->get();
    $micrositeAlias = null;
    foreach ($appearances as $appearance) {
        if (str_contains($appearance->blocks_order, 'digitalproduct_' . $product->id)) {
            $micrositeAlias = $appearance->alias;
            break;
        }
    }
    if (!$micrositeAlias) {
        $firstAppearance = \App\Models\Appearance::where('user_id', $product->user_id)->first();
        $micrositeAlias = $firstAppearance ? $firstAppearance->alias : ($product->user->username ?? '');
    }
    $micrositeUrl = route('public.profile', ['username' => $micrositeAlias]);

    return view('public.checkout-success', compact('product', 'transaction', 'micrositeUrl'));
}

public function checkout(Request $request, $id)
{
    $product = DigitalProduct::findOrFail($id);

    if ($product->is_active === false || $product->is_active === 0) {
        return back()->with('error', 'Produk tidak dapat dibeli karena telah dinonaktifkan oleh Admin.');
    }

    if ($product->user && $product->user->isSuspended()) {
        return back()->with('error', 'Produk tidak dapat dibeli karena akun penjual sedang ditangguhkan.');
    }

    // Ambil qty dari session jika tidak ada permintaan POST
    $qty = $request->isMethod('post')
        ? $request->qty
        : session("cart.qty.$id", 1);

    $customPrice = session("cart.price.$id");
    
    // Panggil Service untuk menghitung harga & membuat Token Midtrans
    try {
        $checkoutData = $this->checkoutService->generateSnapToken(
            $product, 
            $qty, 
            $customPrice, 
            $request->input('name', 'Guest'), 
            $request->input('email', 'guest@example.com')
        );
    } catch (\Exception $e) {
        return back()->with('error', 'Gagal membuat pembayaran: ' . $e->getMessage());
    }

    $totalPrice = $checkoutData['totalPrice'];
    $itemPrice = $checkoutData['itemPrice'];
    $snapToken = $checkoutData['snapToken'];
    $orderId = $checkoutData['orderId'];

    if ($request->isMethod('post')) {
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string',
            'qty' => 'required|integer|min:1',
        ]);

        if ($totalPrice == 0) {
            // Langsung store untuk transaksi gratis (status otomatis success)
            $transaction = $this->checkoutService->storeTransaction([
                'order_id' => $orderId,
                'transaction_status' => 'success',
                'product_id' => $product->id,
                'buyer_email' => $request->email,
                'buyer_name' => $request->name,
                'qty' => $qty,
                'total_price' => 0
            ]);

            $redirectUrl = null;
            $buyerUser = \App\Models\User::where('email', $transaction->buyer_email)->first();
            if ($buyerUser) {
                $redirectUrl = route('public.profile', ['username' => $buyerUser->username]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Produk gratis berhasil didapatkan & email terkirim',
                'redirect' => $redirectUrl
            ]);
        }

        return view('public.checkout', [
            'product' => $product,
            'snapToken' => $snapToken,
            'savedQty' => $qty,
            'itemPrice' => $itemPrice
        ]);
    }

    return view('public.checkout', [
        'product' => $product,
        'snapToken' => $snapToken,
        'savedQty' => $qty,
        'itemPrice' => $itemPrice
    ]);
}
public function midtransCallback(Request $request)
{
    $result = $this->checkoutService->handleCallback();
    
    if ($result['status'] === 404) {
        return response()->json(['error' => $result['message']], 404);
    }

    return response()->json(['message' => $result['message']], 200);
}
public function storeTransaction(Request $request)
{
    $data = $request->validate([
        'order_id' => 'required|string|unique:transactions',
        'transaction_status' => 'required|string',
        'product_id' => 'required|integer|exists:digital_products,id',
        'buyer_email' => 'required|email',
        'buyer_name' => 'required|string',
        'qty' => 'required|integer|min:1',
        'total_price' => 'required|numeric'
    ]);

    $transaction = $this->checkoutService->storeTransaction($data);

    $redirectUrl = null;
    $buyerUser = \App\Models\User::where('email', $transaction->buyer_email)->first();
    if ($buyerUser) {
        $redirectUrl = route('public.profile', ['username' => $buyerUser->username]);
    }

    return response()->json([
        'success' => true,
        'message' => 'Transaction stored & email sent',
        'redirect' => $redirectUrl
    ]);
}




}