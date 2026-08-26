<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupportTicket extends Model
{
    protected $table = 'support_tickets';

    protected $fillable = [
        'ticket_code',
        'user_id',
        'category',
        'subject',
        'message',
        'status',
        'priority',
        'last_replied_at',
    ];

    protected $casts = [
        'last_replied_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(SupportTicketReply::class, 'support_ticket_id')->oldest();
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'payout'  => 'Penarikan / Payout',
            'product' => 'Produk Digital',
            'account' => 'Akun & Keamanan',
            default   => 'Pertanyaan Umum',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'open'        => 'Menunggu Respon',
            'in_progress' => 'Sedang Ditangani',
            'resolved'    => 'Selesai',
            'closed'      => 'Ditutup',
            default       => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'open'        => 'badge-status-open',
            'in_progress' => 'badge-status-progress',
            'resolved'    => 'badge-status-resolved',
            'closed'      => 'badge-status-closed',
            default       => 'badge-status-open',
        };
    }

    public function getPriorityBadgeClassAttribute(): string
    {
        return match ($this->priority) {
            'urgent' => 'badge-priority-urgent',
            'high'   => 'badge-priority-high',
            'medium' => 'badge-priority-medium',
            'low'    => 'badge-priority-low',
            default  => 'badge-priority-medium',
        };
    }
}
