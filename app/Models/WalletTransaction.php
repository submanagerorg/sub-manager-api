<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public const TYPE = [
        'DEPOSIT' => 'deposit',
        'WITHDRAW' => 'withdraw',
        'REVERSAL' => 'reversal'
    ];

    public const STATUS = [
        'PENDING' => 'pending',
        'SUCCESSFUL' => 'successful',
        'FAILED' => 'failed'
    ];

    /**
     * returns wallet of the transaction
     *
     * @return BelongsTo
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }
}
