<?php
namespace App\PaymentProviders;

class PaymentProvider
{
   /**
    * Initialize payment 
    *
    * @param string $paymentUrl
    * @param string $reference
    * @return array
    */
    public static function initiatePaymentResponse(string $paymentUrl, string $reference): array
    {
        return [
            'status' => true,
            'payment_url' => $paymentUrl,
            'reference' => $reference
        ];
    }

    /**
    * Verify Payment
    *
    * @param string $transactionStatus
    * @param string $reference
    * @param float $smount
    * @param string $email
    * @param string $pricingPlan
    * @return array
    */
    public static function verifyPaymentResponse(
        string $transactionStatus, string $reference, float $amount, string $email, string $pricingPlanUid
    ): array
    {
        if (in_array(strtolower($transactionStatus), ['success', 'successful'])){
            $transactionStatus  = 'success';
        }

        return [
            'status' => true,
            'transaction_status' => $transactionStatus,
            'reference' => $reference,
            'amount' => $amount,
            'email' => $email,
            'pricing_plan_uid' => $pricingPlanUid
        ];
    }

    /**
    * Validate Webhook
    *
    * @param string $reference
    * @return array
    */
    public static function validateWebhookResponse(string $reference): array
    {
        return [
            'status' => true,
            'reference' => $reference
        ];
    }

    /**
    * Error Response
    *
    * @param string $errorMessage
    * @return array
    */
    public static function errorResponse(string $errorMessage): array
    {
        return [
            'status' => false,
            'error' => $errorMessage
        ];
    }
}