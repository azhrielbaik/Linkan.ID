<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutDigitalProductRequest;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateDigitalProductQtyRequest;
use App\Models\Appearance;
use App\Models\DigitalProduct;
use App\Models\PlatformSetting;
use App\Models\Transaction;
use App\Models\User;
use App\Services\CheckoutService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected CheckoutService $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    public function show($id)
    {
        $product = DigitalProduct::findOrFail($id);
        $user = $product->user;

        if ($product->is_active === false || $product->is_active === 0) {
            abort(403, 'Produk ini tidak tersedia karena telah dinonaktifkan oleh Admin.');
        }

        if ($user && $user->isSuspended()) {
            abort(403, 'Produk ini tidak tersedia karena akun penjual sedang ditangguhkan.');
        }

        session(["cart.qty.$id" => 1]);

        return view('public.product-detail', compact('product', 'user'));
    }

    public function updateQty(UpdateDigitalProductQtyRequest $request)
    {
        $request->validated();

        session()->put("cart.qty.{$request->product_id}", $request->qty);
        if ($request->has('price')) {
            session()->put("cart.price.{$request->product_id}", $request->price);
        }

        return response()->json(['status' => 'success']);
    }

    public function checkoutSuccess(Request $request, $id)
    {
        $product = DigitalProduct::findOrFail($id);

        $orderId = $request->query('order_id');
        $transaction = null;
        if ($orderId) {
            $transaction = Transaction::where('order_id', $orderId)->first();
        }

        $appearances = Appearance::where('user_id', $product->user_id)->get();
        $micrositeAlias = null;
        foreach ($appearances as $appearance) {
            $blocksOrder = is_array($appearance->blocks_order)
                ? implode(',', $appearance->blocks_order)
                : (string) $appearance->blocks_order;
            if (str_contains($blocksOrder, 'digitalproduct_'.$product->id)) {
                $micrositeAlias = $appearance->alias;
                break;
            }
        }
        if (! $micrositeAlias) {
            $firstAppearance = Appearance::where('user_id', $product->user_id)->first();
            $micrositeAlias = $firstAppearance ? $firstAppearance->alias : ($product->user->username ?? '');
        }
        $micrositeUrl = route('public.profile', ['username' => $micrositeAlias]);

        return view('public.checkout-success', compact('product', 'transaction', 'micrositeUrl'));
    }

    public function checkout(CheckoutDigitalProductRequest $request, $id)
    {
        $product = DigitalProduct::findOrFail($id);

        $isCheckoutDisabled = (bool) PlatformSetting::get('disable_checkout', 0);
        $disableCheckoutMessage = PlatformSetting::get('disable_checkout_message')
            ?: 'Layanan checkout sedang dinonaktifkan sementara untuk pemeliharaan sistem. Silakan coba beberapa saat lagi.';

        if ($isCheckoutDisabled && $request->isMethod('post')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $disableCheckoutMessage,
                ], 403);
            }

            return back()->with('error', $disableCheckoutMessage);
        }

        if ($product->is_active === false || $product->is_active === 0) {
            return back()->with('error', 'Produk tidak dapat dibeli karena telah dinonaktifkan oleh Admin.');
        }

        if ($product->user && $product->user->isSuspended()) {
            return back()->with('error', 'Produk tidak dapat dibeli karena akun penjual sedang ditangguhkan.');
        }

        $qty = $request->isMethod('post')
            ? $request->qty
            : session("cart.qty.$id", 1);

        $customPrice = session("cart.price.$id");

        try {
            $checkoutData = $this->checkoutService->generateSnapToken(
                $product,
                $qty,
                $customPrice,
                $request->input('name', 'Guest'),
                $request->input('email', 'guest@example.com')
            );
        } catch (Exception $e) {
            return back()->with('error', 'Gagal membuat pembayaran: '.$e->getMessage());
        }

        $totalPrice = $checkoutData['totalPrice'];
        $itemPrice = $checkoutData['itemPrice'];
        $snapToken = $checkoutData['snapToken'];
        $orderId = $checkoutData['orderId'];

        if ($request->isMethod('post')) {
            $request->validated();

            if ($totalPrice == 0) {
                $transaction = $this->checkoutService->storeTransaction([
                    'order_id' => $orderId,
                    'transaction_status' => 'success',
                    'product_id' => $product->id,
                    'buyer_email' => $request->email,
                    'buyer_name' => $request->name,
                    'qty' => $qty,
                    'total_price' => 0,
                ]);

                $redirectUrl = null;
                $buyerUser = User::where('email', $transaction->buyer_email)->first();
                if ($buyerUser) {
                    $redirectUrl = route('public.profile', ['username' => $buyerUser->username]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Produk gratis berhasil didapatkan & email terkirim',
                    'redirect' => $redirectUrl,
                ]);
            }

            return view('public.checkout', [
                'product' => $product,
                'snapToken' => $snapToken,
                'savedQty' => $qty,
                'itemPrice' => $itemPrice,
                'orderId' => $orderId,
                'isCheckoutDisabled' => $isCheckoutDisabled,
                'disableCheckoutMessage' => $disableCheckoutMessage,
            ]);
        }

        return view('public.checkout', [
            'product' => $product,
            'snapToken' => $snapToken,
            'savedQty' => $qty,
            'itemPrice' => $itemPrice,
            'orderId' => $orderId,
            'isCheckoutDisabled' => $isCheckoutDisabled,
            'disableCheckoutMessage' => $disableCheckoutMessage,
        ]);
    }

    public function midtransCallback(Request $request)
    {
        Log::info('Midtrans Webhook Raw Payload: ', $request->all());

        try {
            $result = $this->checkoutService->handleCallback();
        } catch (Exception $e) {
            Log::error('Midtrans Webhook Error: '.$e->getMessage());

            return response()->json(['error' => 'Webhook error'], 400);
        }

        if ($result['status'] === 404) {
            return response()->json(['error' => $result['message']], 404);
        }

        return response()->json(['message' => $result['message']], 200);
    }

    public function storeTransaction(StoreTransactionRequest $request)
    {
        $data = $request->validated();

        $transaction = $this->checkoutService->storeTransaction($data);

        $redirectUrl = null;
        $buyerUser = User::where('email', $transaction->buyer_email)->first();
        if ($buyerUser) {
            $redirectUrl = route('public.profile', ['username' => $buyerUser->username]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Transaction stored & email sent',
            'redirect' => $redirectUrl,
        ]);
    }
}
