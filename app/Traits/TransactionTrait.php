<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait TransactionTrait
{
    /**
     * Generate transaction reference
     * @param string $label
     * @return string
     */
    public function generateReference(string $label)
    {
        return Str::upper(sprintf(
            '%s-%s-%s-%s-%s',
            'TRF',
            'SBSY',
            $label,
            now()->format('YmdHis'),
            Str::random(3)
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
        $maxFeeCap = 2200;
        $minFeeCap = 50;
        $extraFeeWaivedMaxAmount = 2500;

        $fee = (1.7 / 100) * $amount;

        if($fee < $minFeeCap) {
            return $minFeeCap;
        }

        if($amount > $extraFeeWaivedMaxAmount){
            $fee += 100;
        }

        if($fee > $maxFeeCap) {
            return $maxFeeCap;
        }

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
