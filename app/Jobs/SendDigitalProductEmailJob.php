<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
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
            
            \Log::info('Email sent successfully', [
                'to' => $this->transaction->buyer_email,
                'product' => $this->product->title
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to send email', [
                'error' => $e->getMessage(),
                'transaction_id' => $this->transaction->id
            ]);
            
            // Retry job jika gagal
            $this->release(300); // Retry setelah 5 menit
        }
    }

    public function failed(\Throwable $exception)
    {
        \Log::error('Job failed', [
            'error' => $exception->getMessage(),
            'transaction_id' => $this->transaction->id
        ]);
    }
} 