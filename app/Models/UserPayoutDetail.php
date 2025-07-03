<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPayoutDetail extends Model
{
    use HasFactory;

    protected $table = 'user_payout_details';

    protected $fillable = [
        'user_id',
        'method_type',
        'account_name',
        'account_number',
        'bank_name',
    ];

    /**
     * Get the user that owns the payout detail.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 