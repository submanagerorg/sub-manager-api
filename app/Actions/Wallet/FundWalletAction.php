<?php

namespace App\Actions\Wallet;

use App\Models\Transaction;
use App\Models\User;
use App\Traits\FormatApiResponse;
use App\Traits\TransactionTrait;
use Illuminate\Http\JsonResponse;
use Throwable;

class FundWalletAction
{
    use FormatApiResponse, TransactionTrait;

    /**
     * Fund wallet
     *
     * @param User $user
     * @param float $amount
     * 
     * @return JsonResponse
     */
    public function execute(User $user, float $amount): JsonResponse
    {
        $paymentProvider = $this->getPaymentProvider();
        $fee = $this->getTransactionFee($amount);

        try {
            $paymentData = [
                'reference' => $this->generateReference(),
                'amount' => $amount + $fee,
                'email' => $user->email,
                'currency' => $this->getDefaultCurrency()['CODE'],
                'metadata' => [
                    'type' =>  'wallet',
                    'fee' => $fee
                ],
                'callback_url' => config('app.webapp_url') . "/payment/callback?email={$user->email}"
            ];

            $response = (new $paymentProvider())->initiatePayment($paymentData);

            if ($response['status'] !== true) {
                return $this->formatApiResponse(500, 'Unable to initiate payment.');
            }

            $data = [
                'payment_url' => $response['payment_url'],
                'reference' => $response['reference']
            ];

            return $this->formatApiResponse(200, 'Payment successfully initiated.', $data);
        } catch (Throwable $e) {
            report($e);
            return $this->formatApiResponse(500, 'Error occured', [], $e->getMessage());
        }
    }
}
