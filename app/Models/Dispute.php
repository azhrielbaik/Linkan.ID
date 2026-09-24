<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dispute extends Model
{
    protected $fillable = [
        'dispute_code',
        'transaction_id',
        'order_id',
        'seller_id',
        'product_id',
        'buyer_name',
        'buyer_email',
        'buyer_phone',
        'amount',
        'reason',
        'description',
        'evidence_file',
        'refund_bank_name',
        'refund_account_name',
        'refund_account_number',
        'status',
        'admin_notes',
        'refund_reference',
        'resolved_by',
        'resolved_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'resolved_at' => 'datetime',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(DigitalProduct::class, 'product_id');
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    /**
     * Label alasan sengketa dalam Bahasa Indonesia yang ramah pengguna.
     */
    public function getReasonLabelAttribute(): string
    {
        return match ($this->reason) {
            'broken_link' => 'Link Unduhan Rusak / Expired',
            'corrupted_file' => 'File Rusak / Tidak Bisa Dibuka',
            'misleading_description' => 'Tidak Sesuai Deskripsi Produk',
            'fraud_scam' => 'Dugaan Penipuan / Pelanggaran',
            default => 'Lainnya',
        };
    }

    /**
     * Label status sengketa dalam Bahasa Indonesia.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Tindakan',
            'under_review' => 'Dalam Investigasi',
            'resolved_refunded' => 'Refund Selesai',
            'resolved_rejected' => 'Sengketa Ditolak',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    /**
     * CSS class badge status sengketa.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'dsp-badge-pending',
            'under_review' => 'dsp-badge-review',
            'resolved_refunded' => 'dsp-badge-refunded',
            'resolved_rejected' => 'dsp-badge-rejected',
            default => 'dsp-badge-default',
        };
    }
}
