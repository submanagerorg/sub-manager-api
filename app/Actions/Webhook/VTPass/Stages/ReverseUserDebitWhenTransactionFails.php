<?php 

namespace App\Actions\Webhook\VTPass\Stages;

use App\Actions\Webhook\VTPass\HandleVTPassWebhookState;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Traits\TransactionTrait;

class ReverseUserDebitWhenTransactionFails
{
    use TransactionTrait;

    public function handle(HandleVTPassWebhookState $state)
    {
        $metaData = $state->getMetaData();

        $user = User::find($metaData['user_id']);

        if ($state->transactionFailed() && $user) {
            $user->wallet->credit($this->generateReference(Wallet::LABEL), $metaData['full_amount'], 0, WalletTransaction::TYPE['REVERSAL'], $metaData['service_name'] . ' Payment Reversal');
        }
    }
}