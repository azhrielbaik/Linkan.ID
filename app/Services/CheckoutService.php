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
        return DB::transaction(function () use ($data) {
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

            $transaction = Transaction::updateOrCreate(
                ['order_id' => $data['order_id']],
                [
                    'status' => $status,
                    'product_id' => $data['product_id'],
                    'buyer_email' => $data['buyer_email'],
                    'buyer_name' => $data['buyer_name'],
                    'qty' => $data['qty'],
                    'total_price' => $data['total_price'],
                ]
            );

            Log::info('Store Transaction - Upserted Transaction ID: ' . $transaction->id);

            // Hanya kirim email dari frontend jika transaksi otomatis sukses (misal: produk gratis)
            if ($status === TransactionStatus::SUCCESS) {
                $product = DigitalProduct::where('id', $data['product_id'])->lockForUpdate()->first();
                if ($product) {
                    Mail::to($transaction->buyer_email)->send(
                        new SendDigitalProductMail($product, $transaction->buyer_name, $transaction)
                    );
                }
            }

            return $transaction;
        });
    }

    /**
     * Handle Midtrans Webhook Callback
     */
    public function handleCallback(array $payload = null)
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        
        // Cek status langsung menggunakan order_id dari payload untuk menghindari isu php://input 
        try {
            $payloadArray = request()->all();
            if (empty($payloadArray['order_id'])) {
                return ['status' => 400, 'message' => 'Missing order_id in payload'];
            }
            
            $status_response = \Midtrans\Transaction::status($payloadArray['order_id']);
            $orderId = $status_response->order_id;
            $transactionStatusRaw = $status_response->transaction_status;
            
        } catch (\Exception $e) {
            Log::error('Midtrans Callback Error: ' . $e->getMessage());
            return ['status' => 400, 'message' => 'Invalid notification payload'];
        }

        $transactionStatus = MidtransStatus::tryFrom($transactionStatusRaw);

        Log::info('Midtrans Callback - Transaction Status: ' . ($transactionStatus?->value ?? $transactionStatusRaw));
        Log::info('Midtrans Callback - Order ID: ' . $orderId);

        return DB::transaction(function () use ($orderId, $transactionStatus) {
            // Kunci baris transaksi ini untuk mencegah eksekusi webhook ganda secara paralel
            $trx = Transaction::where('order_id', $orderId)->lockForUpdate()->first();

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
                if ($product && $product->user_id) {
                    $sellerId = $product->user_id;
                    
                    Log::info('Midtrans Callback - Updating seller balance');
                    Log::info('Midtrans Callback - Seller ID: ' . $sellerId);
                    Log::info('Midtrans Callback - Amount to add: ' . $trx->total_price);
                    
                    // Kunci baris user sebelum mengubah balance untuk mencegah race condition dengan payout
                    DB::table('users')
                        ->where('id', $sellerId)
                        ->lockForUpdate()
                        ->increment('balance', $trx->total_price);

                    // Kirim email produk digital menggunakan template SendDigitalProductMail
                    Mail::to($trx->buyer_email)->send(
                        new SendDigitalProductMail($product, $trx->buyer_name, $trx)
                    );
                }
            } elseif ($transactionStatus === MidtransStatus::CANCEL || $transactionStatus === MidtransStatus::DENY || $transactionStatus === MidtransStatus::EXPIRE) {
                $trx->status = TransactionStatus::FAILED;
                $trx->save();
            } elseif ($transactionStatus === MidtransStatus::PENDING) {
                $trx->status = TransactionStatus::PENDING;
                $trx->save();
            }

            return ['status' => 200, 'message' => 'Callback processed'];
        });
    }
}
