<?php

namespace App\Models;

use App\Traits\TransactionTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Wallet extends Model
{
    use HasFactory, TransactionTrait;

    protected $guarded = ['id'];

    public const LABEL = "WAL";

    /**
     * Returns the user of the subscription.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * returns wallet transactions
     *
     * @return HasMany
     */
    public function wallet_transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    /**
     * returns wallet histories
     *
     * @return HasMany
     */
    public function wallet_histories(): HasMany
    {
        return $this->hasMany(WalletHistory::class);
    }

    public function credit(string $reference, float $amount, float $fee, string $transactionType, string $description)
    {
        $isTransactionActive = DB::transactionLevel() > 0;

        if (!$isTransactionActive) {
            DB::beginTransaction();
        }

        try {
            $this->lockForUpdate();

            $this->updateBalanceAndCreateRecords($reference, WalletHistory::TYPE['CREDIT'], $amount, $fee, $transactionType, $description);

            if (!$isTransactionActive) {
                DB::commit();
            }
        } catch (\Exception $e) {
            if (!$isTransactionActive) {
                DB::rollBack();
            }
            throw $e;
        }
    }

    public function debit(string $reference, float $amount, float $fee, string $transactionType, string $description)
    {
        $isTransactionActive = DB::transactionLevel() > 0;

        if (!$isTransactionActive) {
            DB::beginTransaction();
        }

        try {
            $this->lockForUpdate();

             //add fee to the amount to be deducted
             $amount += $fee;

            if ($this->balance < $amount) {
                throw new \Exception("Insufficient balance.");
            }

            $this->updateBalanceAndCreateRecords($reference, WalletHistory::TYPE['DEBIT'], -$amount, $fee, $transactionType, $description);

            if (!$isTransactionActive) {
                DB::commit();
            }
        } catch (\Exception $e) {
            if (!$isTransactionActive) {
                DB::rollBack();
            }
            throw $e;
        }
    }


    protected function updateBalanceAndCreateRecords(string $reference, string $type, float $amount, float $fee, string $transactionType, string $description)
    {
        $previousBalance = $this->balance;
        $currentBalance = $previousBalance + $amount;

        // Create wallet transaction
        $transaction = $this->wallet_transactions()->create([
            'reference' => $reference,
            'amount' => $amount,
            'fee' => $fee,
            'status' => WalletTransaction::STATUS['SUCCESSFUL'],
            'type' => $transactionType,
            'description' => $description,
        ]);

        // Create wallet history
        $this->wallet_histories()->create([
            'previous_balance' => $previousBalance,
            'current_balance' => $currentBalance,
            'amount' => $amount,
            'type' => $type,
            'transaction_id' => $transaction->id,
        ]);

        // Update wallet balance
        $this->update(['balance' => $currentBalance]);
    }
}
