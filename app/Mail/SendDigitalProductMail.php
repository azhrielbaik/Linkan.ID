<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\DigitalProduct;
use App\Helpers\ReviewTokenHelper;

class SendDigitalProductMail extends Mailable
{
    use Queueable, SerializesModels;

    public $product;
    public $buyerName;
    public $transaction;
    public $reviewUrl;
    public $disputeUrl;

    public function __construct(DigitalProduct $product, $buyerName, $transaction = null)
    {
        $this->product     = $product;
        $this->buyerName   = $buyerName;
        $this->transaction = $transaction;

        if ($transaction && !empty($transaction->order_id)) {
            $token = ReviewTokenHelper::generate($transaction->order_id);
            $this->reviewUrl  = url('/id/review/' . $token);
            $this->disputeUrl = url('/id/bantuan/sengketa');
        }
    }

    public function build()
    {
        $orderId = $this->transaction ? $this->transaction->order_id : time();
        
        return $this->subject('Pesanan Produk Digital Anda: ' . $this->product->title . ' [#' . $orderId . ']')
                    ->view('emails.send-digital-product')
                    ->with([
                        'product'     => $this->product,
                        'buyerName'   => $this->buyerName,
                        'transaction' => $this->transaction,
                        'reviewUrl'   => $this->reviewUrl,
                        'disputeUrl'  => $this->disputeUrl,
                    ]);
    }
}
