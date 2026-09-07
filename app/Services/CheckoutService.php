<?php

namespace App\Services;

use App\Mail\SendDigitalProductMail;
use App\Models\DigitalProduct;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use App\Enums\TransactionStatus;
use App\Enums\MidtransStatus;

class CheckoutService
{
    /**
     * Generate Midtrans Snap Token for checkout
     */
    public function generateSnapToken(DigitalProduct $product, int $qty, $customPrice, string $buyerName, string $buyerEmail): array
    {
        $itemPrice = $product->price;
        if ($product->pricing_type === 'pwyw' && $customPrice && $customPrice >= $product->price_min) {
            $itemPrice = $customPrice;
        }

        $totalPrice = $itemPrice * $qty;
        $snapToken = null;
        $orderId = 'ORDER-' . uniqid();

        if ($totalPrice > 0) {
            Config::$serverKey = config('services.midtrans.server_key');
            Config::$isProduction = config('services.midtrans.is_production');
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $totalPrice,
                ],
                'customer_details' => [
                    'first_name' => $buyerName ?: 'Guest',
                    'email' => $buyerEmail ?: 'guest@example.com',
                ],
                'item_details' => [[
                    'id' => $product->id,
                    'price' => $itemPrice,
                    'quantity' => $qty,
                    'name' => $product->title,
                ]],
            ];

            try {
                $snapToken = Snap::getSnapToken($params);
            } catch (\Exception $e) {
                throw $e;
            }
        }

        return [
            'snapToken' => $snapToken,
            'orderId' => $orderId,
            'totalPrice' => $totalPrice,
            'itemPrice' => $itemPrice,
        ];
    }

    /**
     * Store transaction from frontend JS callback
     */
    public function storeTransaction(array $data): Transaction
    {
        Log::info('Store Transaction - Initial Status: ' . $data['transaction_status']);

        // Ubah status dari Midtrans ke status yang kita gunakan
        // PERBAIKAN: Untuk mencegah race condition dengan webhook, storeTransaction 
        // hanya menyimpan status 'pending' untuk transaksi berbayar.
        if ($data['total_price'] == 0) {
            $status = TransactionStatus::SUCCESS;
        } else {
            $status = TransactionStatus::PENDING;
        }

        Log::info('Store Transaction - Converted Status: ' . $status->value);

        $transaction = Transaction::create([
            'order_id' => $data['order_id'],
            'status' => $status,
            'product_id' => $data['product_id'],
            'buyer_email' => $data['buyer_email'],
            'buyer_name' => $data['buyer_name'],
            'qty' => $data['qty'],
            'total_price' => $data['total_price'],
        ]);

        Log::info('Store Transaction - Created Transaction ID: ' . $transaction->id);

        // Kirim email dari frontend agar user langsung dapat akses tanpa harus menunggu webhook
        $product = DigitalProduct::find($data['product_id']);
        if ($product) {
            Mail::to($transaction->buyer_email)->send(
                new SendDigitalProductMail($product, $transaction->buyer_name, $transaction)
            );
        }

        return $transaction;
    }

    /**
     * Handle Midtrans Webhook Callback
     */
    public function handleCallback(array $payload = null)
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');

        // Jika dipanggil oleh route webhook Midtrans secara standard
        $notif = new Notification();

        $transactionStatus = MidtransStatus::tryFrom($notif->transaction_status);
        $orderId = $notif->order_id;

        Log::info('Midtrans Callback - Transaction Status: ' . ($transactionStatus?->value ?? $notif->transaction_status));
        Log::info('Midtrans Callback - Order ID: ' . $orderId);

        $trx = Transaction::where('order_id', $orderId)->first();

        if (!$trx) {
            Log::error('Midtrans Callback - Transaction not found for order ID: ' . $orderId);
            return ['status' => 404, 'message' => 'Transaction not found'];
        }

        // Mekanisme Pengamanan: Jika transaksi sudah tercatat success di DB,
        // hentikan eksekusi agar tidak terjadi double penambahan saldo
        if ($trx->status === TransactionStatus::SUCCESS) {
            Log::info('Midtrans Callback - Transaction already success for order ID: ' . $orderId . '. Skipping to prevent double balance.');
            return ['status' => 200, 'message' => 'Transaction already processed'];
        }

        // Ubah status dari Midtrans ke status yang kita gunakan
        if ($transactionStatus === MidtransStatus::CAPTURE || $transactionStatus === MidtransStatus::SETTLEMENT) {
            $trx->status = TransactionStatus::SUCCESS;
            $trx->save();

            Log::info('Midtrans Callback - Updating transaction status to success');
            Log::info('Midtrans Callback - Transaction ID: ' . $trx->id);
            Log::info('Midtrans Callback - Amount: ' . $trx->total_price);

            // Update balance seller
            $product = $trx->product;
            if ($product && $product->user) {
                $seller = $product->user;
                
                Log::info('Midtrans Callback - Updating seller balance');
                Log::info('Midtrans Callback - Seller ID: ' . $seller->id);
                Log::info('Midtrans Callback - Amount to add: ' . $trx->total_price);
                
                DB::table('users')
                    ->where('id', $seller->id)
                    ->increment('balance', $trx->total_price);

                // Kirim email produk digital cadangan (raw text)
                $link = $product->platform_type === 'upload'
                    ? asset('storage/' . $product->platform_file)
                    : $product->platform_url;

                Mail::raw("Terima kasih telah membeli produk digital. Berikut link download Anda:\n\n$link", function ($message) use ($trx) {
                    $message->to($trx->buyer_email)
                            ->subject('Produk Digital Anda');
                });
            }
        } elseif ($transactionStatus === MidtransStatus::CANCEL || $transactionStatus === MidtransStatus::DENY || $transactionStatus === MidtransStatus::EXPIRE) {
            $trx->status = TransactionStatus::FAILED;
            $trx->save();
        } elseif ($transactionStatus === MidtransStatus::PENDING) {
            $trx->status = TransactionStatus::PENDING;
            $trx->save();
        }

        return ['status' => 200, 'message' => 'Callback processed'];
    }
}
