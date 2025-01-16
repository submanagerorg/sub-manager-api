<?php 

namespace App\Actions\ServicePayment\PayPipeline\Stages;

use App\Actions\ServicePayment\PayPipeline\HandlePaymentState;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Traits\TransactionTrait;
use Closure;
use Illuminate\Support\Facades\Log;

class ReverseUserDebitWhenTransactionFails
{
    use TransactionTrait;

    public function handle(HandlePaymentState $state, Closure $next)
    {
        if ($state->transactionFailed()) {
            $requestData = $state->getRequestData();

            $user = User::find($requestData['user_id']);

            if ($state->transactionFailed() && $user) {
                $user->wallet->credit($this->generateReference(Wallet::LABEL), $requestData['full_amount'], 0, WalletTransaction::TYPE['REVERSAL'], ucfirst($requestData['service_name']) . ' Payment Reversal');

                Log::info("Subcription money refunded");
            }
        }

        return $next($state);
    }
}