<?php

namespace App\Actions\PlanPayment;

use App\Models\PricingPlan;
use App\Models\Subscription;
use App\Traits\FormatApiResponse;
use App\Traits\TransactionTrait;
use Illuminate\Http\JsonResponse;
use Throwable;

class InitiatePaymentAction
{
    use FormatApiResponse, TransactionTrait;

    /**
     * Initiate payment
     *
     * @param array $data
     * @return JsonResponse
     */
    public function execute(array $data): JsonResponse
    {
        $paymentProvider = $this->getPaymentProvider();

        try {

            $plan = PricingPlan::where('uid', $data['pricing_plan_uid'])->first();
            $fee = $this->getTransactionFee($plan->amount);

            $paymentData = [
                'reference' => $this->generateReference(Subscription::LABEL),
                'amount' => $plan->amount + $fee,
                'email' => $data['email'],
                'currency' => $this->getDefaultCurrency()['CODE'],
                'metadata' => [
                    'type' => 'subscription',
                    'pricing_plan_uid' => $plan->uid,
                    'fee' => $fee
                ],
                'callback_url' => config('app.website_url') . "/payment/callback?planuid={$plan->uid}&email={$data['email']}"
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
