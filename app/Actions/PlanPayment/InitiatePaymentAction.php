<?php
namespace App\Actions\PlanPayment;

use App\Models\PricingPlan;
use App\Models\Transaction;
use App\Traits\FormatApiResponse;
use Throwable;

class InitiatePaymentAction
{
    use FormatApiResponse;

   /**
    * Initiate payment
    *
    * @param array $data
    * @return JsonResponse
    */
    public function execute(array $data)
    {
        $paymentProvider = Transaction::getPaymentProvider();

        try{

            $plan = PricingPlan::where('uid', $data['pricing_plan_uid'])->first();

            $paymentData = [
                'reference' => Transaction::generateReference(),
                'plan' => $plan,
                'email' => $data['email'],
                'currency' => Transaction::DEFAULT_CURRENCY['CODE']
            ];

            $response = (new $paymentProvider())->initiatePayment($paymentData); 

            if($response['status'] !== true){
                return $this->formatApiResponse(500, 'Unable to initiate payment.');
            }
    
            $data = [
                'payment_url' => $response['payment_url'],
                'reference' => $response['reference']
            ];
    
            return $this->formatApiResponse(200, 'Payment successfully initiated.', $data);
            

        } catch(Throwable $e) {
            logger($e);
            return $this->formatApiResponse(500, 'Error occured', [], $e->getMessage());
        }
       
    }
}