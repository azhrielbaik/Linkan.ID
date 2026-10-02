<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendDigitalProductMail;
use App\Models\DigitalProduct;
use App\Models\Transaction;

class SendDigitalProductEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $product;
    protected $transaction;

    public function __construct(DigitalProduct $product, Transaction $transaction)
    {
        $this->product = $product;
        $this->transaction = $transaction;
    }

    public function handle()
    {
        try {
            Mail::to($this->transaction->buyer_email)
                ->send(new SendDigitalProductMail($this->product, $this->transaction->buyer_name, $this->transaction));
            
            Log::channel('jobs')->info('Digital product email sent successfully', [
                'job' => 'SendDigitalProductEmailJob',
                'product_id' => $this->product->id,
                'recipient_email' => $this->transaction->buyer_email,
            ]);
        } catch (\Exception $e) {
            Log::channel('jobs')->error('Failed to send digital product email', [
                'job' => 'SendDigitalProductEmailJob',
                'product_id' => $this->product->id,
                'recipient_email' => $this->transaction->buyer_email,
                'error_message' => $e->getMessage(),
                'attempt_number' => $this->attempts(),
            ]);
            
            // Retry job jika gagal
            $this->release(300); // Retry setelah 5 menit
        }
    }

    public function failed(\Throwable $exception)
    {
        Log::channel('jobs')->error('Send digital product email job failed permanently', [
            'job' => 'SendDigitalProductEmailJob',
            'product_id' => $this->product->id,
            'recipient_email' => $this->transaction->buyer_email,
            'error_message' => $exception->getMessage(),
            'attempt_number' => $this->attempts(),
        ]);
    }
} 