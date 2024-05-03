<?php
namespace App\Actions\PlanPayment;

use App\Models\Currency;
use App\Models\PricingPlan;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use App\PaymentProviders\Paystack;
use App\Traits\FormatApiResponse;
use Throwable;

class InitiatePaymentAction
{
    use FormatApiResponse;

   /**
    *
    * @param array $data
    * @return JsonResponse
    */
    public function execute(array $data)
    {
        try{
            //create user after payment is successful

            $plan = PricingPlan::where('uid', $data['pricing_plan_uid'])->first();

            $paymentData = [
                'reference' => Transaction::generateReference(),
                'plan' => $plan,
                'email' => $data['email'],
                'currency' => Currency::DEFAULT_CURRENCY
            ];

            $response = (new Paystack())->initiatePayment($paymentData); 

            if($response->status == 'true'){
                $data = [
                    'payment_url' => $response->data->authorization_url,
                    'reference' => $response->data->reference
                ];
        
                return $this->formatApiResponse(200, 'Payment successfully initiated.', $data);
            }
    
           

        } catch(Throwable $e) {
            logger($e);
            return $this->formatApiResponse(500, 'Error occured', [], $e->getMessage());
        }
       
    }
}