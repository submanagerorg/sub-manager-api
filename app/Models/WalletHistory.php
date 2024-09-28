<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletHistory extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public const TYPE = [
        'CREDIT' => 'credit',
        'DEBIT' => 'debit'
    ];

    /**
     * returns wallet
     *
     * @return BelongsTo
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * returns wallet transactions
     *
     * @return BelongsTo
     */
    public function wallet_transaction(): BelongsTo
    {
        return $this->belongsTo(WalletTransaction::class);
    }
}
