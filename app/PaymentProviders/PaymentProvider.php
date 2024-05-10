<?php
namespace App\PaymentProviders;
 
use App\Traits\FormatApiResponse;

class PaymentProvider
{
    use FormatApiResponse;

   /**
    * Initialize payment 
    *
    * @param $paymentUrl
    * @param $reference
    * @return JsonResponse
    */
    public static function initiatePaymentResponse($paymentUrl, $reference)
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
    * @param $transactionStatus
    * @param $reference
    * @param $smount
    * @param $email
    * @param $pricingPlan
    * @return JsonResponse
    */
    public static function verifyPaymentResponse($transactionStatus, $reference, $amount, $email, $pricingPlan)
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
            'pricing_plan' => $pricingPlan
        ];
    }

    /**
    * Validate Webhook
    *
    * @param $reference
    * @return JsonResponse
    */
    public static function validateWebhookResponse($reference)
    {
        return [
            'status' => true,
            'reference' => $reference
        ];
    }

    /**
    * Error Response
    *
    * @param $errorMessage
    * @return JsonResponse
    */
    public static function errorResponse($errorMessage)
    {
        return [
            'status' => false,
            'error' => $errorMessage
        ];
    }
}