<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\DigitalProduct;

class SendDigitalProductMail extends Mailable
{
    use Queueable, SerializesModels;

    public $product;
    public $buyerName;
    public $transaction;  // tambah properti transaction

    // Terima data transaksi di konstruktor juga
    public function __construct(DigitalProduct $product, $buyerName, $transaction = null)
    {
        $this->product = $product;
        $this->buyerName = $buyerName;
        $this->transaction = $transaction;
    }

    public function build()
    {
        return $this->view('emails.send-digital-product')
                    ->with([
                        'product' => $this->product,
                        'buyerName' => $this->buyerName,
                        'transaction' => $this->transaction,
                    ]);
    }
}
