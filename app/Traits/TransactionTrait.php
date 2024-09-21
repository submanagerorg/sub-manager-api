<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait TransactionTrait
{
    /**
     * Generate transaction reference
     *
     * @return string
     */
    public function generateReference()
    {
        return Str::upper(sprintf(
            '%s-%s-%s-%s',
            'TRF',
            'SBSY',
            now()->format('YmdHis'),
            Str::random(6)
        ));
    }

    /**
     * Retrieve payment provider
     *
     * @return string
     */
    public function getPaymentProvider()
    {
        $paymentProvider = "App\PaymentProviders\\" . config('providers.payment_provider');

        return $paymentProvider;
    }

    /**
     * Calculate transaction fee
     *
     * @return float
     */
    public function getTransactionFee($amount)
    {
        $fee = (1.5 / 100) * $amount;

        return $fee;
    }

    public function getDefaultCurrency()
    {
        return [
            'CODE' => 'NGN',
            'SIGN' =>  '₦'
        ];
    }
}
