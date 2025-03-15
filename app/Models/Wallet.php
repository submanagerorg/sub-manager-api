<?php

namespace App\Models;

use App\Exceptions\InsufficientFundsException;
use App\Notifications\WalletDebitedEmail;
use App\Notifications\WalletFundedEmail;
use App\Notifications\WalletReversedEmail;
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
            $this->sendEmail($reference, $transactionType);

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
                throw new InsufficientFundsException();
            }

            $this->updateBalanceAndCreateRecords($reference, WalletHistory::TYPE['DEBIT'], -$amount, $fee, $transactionType, $description);
            $this->sendEmail($reference, $transactionType);

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
            'wallet_transaction_id' => $transaction->id,
        ]);

        // Update wallet balance
        $this->update(['balance' => $currentBalance]);
    }

    protected function sendEmail($reference, $transactionType) {
        $walletTransaction = WalletTransaction::where('reference', $reference)->first();
        $user = $walletTransaction->wallet->user;

        $data = [
            'amount' => abs($walletTransaction->amount),
            'description' => $walletTransaction->description,
            'reference' => $walletTransaction->reference,
            'dateTime' => $walletTransaction->created_at,
            'balance' => $walletTransaction->wallet->balance,
        ];

        if($transactionType == WalletTransaction::TYPE['DEPOSIT']) {
            $user->notify(new WalletFundedEmail($data));
        }

        if($transactionType == WalletTransaction::TYPE['WITHDRAW']) {
            $user->notify(new WalletDebitedEmail($data));
        }

        if($transactionType == WalletTransaction::TYPE['REVERSAL']) {
            $user->notify(new WalletReversedEmail($data));
        }
    }
}
