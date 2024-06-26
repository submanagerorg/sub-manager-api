<?php
namespace App\Actions\PlanPayment;

use App\Mail\SetPasswordMail;
use App\Mail\SuccessfulPaymentMail;
use App\Mail\WelcomeMail;
use App\Models\PricingPlan;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WebhookLog;
use App\Traits\FormatApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;
use Illuminate\Support\Str;

class ProcessWebhookAction
{
    use FormatApiResponse;

   /**
    * Process Webhook
    *
    * @param Request $request
    * @return JsonResponse
    */
    public function execute(Request $request): JsonResponse
    {
        $webhookLog = WebhookLog::create([
            'request' => json_encode([
                'body' => $request->all(),
                'headers' => $request->header(),
            ])
        ]);

        $paymentProvider = Transaction::getPaymentProvider();

        DB::beginTransaction();

        try{

            $paymentProvider = (new $paymentProvider());
            $validationResponse = $paymentProvider->validateWebhook($request); 

            if($validationResponse['status'] !== true) {
                $this->updateWebhookLog($webhookLog, 'Request could not be validated.');

                DB::commit();
                return $this->formatApiResponse(403, 'Request could not be validated.');
            }

            $verifyPayment = $paymentProvider->verifyPayment($validationResponse['reference']); 

            if($verifyPayment['status'] !== true){
                $this->updateWebhookLog($webhookLog, 'Transaction could not be verified.');

                DB::commit();
                return $this->formatApiResponse(403, 'Transaction could not be verified.');
            }

            if($verifyPayment['transaction_status'] !== 'success'){
                $this->updateWebhookLog($webhookLog, 'OK - Transaction is not successful');

                DB::commit();
                return $this->formatApiResponse(200, 'OK');
            }

            if(Transaction::where('reference', $verifyPayment['reference'])->first()){
               $this->updateWebhookLog($webhookLog, 'OK - Transaction already exists');

                DB::commit();
                return $this->formatApiResponse(200, 'OK');
            }

            $user = User::where('email', $verifyPayment['email'])->first();

            $pricingPlan = PricingPlan::where('uid', $verifyPayment['pricing_plan_uid'])->first();

            if(!$user){
                $this->createUser($verifyPayment['email'], $pricingPlan, $verifyPayment['amount']);
                
                $user = User::where('email', $verifyPayment['email'])->first();
            }

            $user->addUserPricingPlan($pricingPlan);

            $this->createTransaction($user, $pricingPlan, $verifyPayment['amount'], $verifyPayment['reference']);

            $this->updateWebhookLog($webhookLog, 'OK');

            DB::commit();
            return $this->formatApiResponse(200, 'OK');

        } catch(Throwable $e) {
            DB::rollback();

            logger($e);
            return $this->formatApiResponse(500, 'Error occured', [], $e->getMessage());
        }
       
    }


    /**
    * Create User 
    *
    * @param string $email
    * @param PricingPlan $pricingPlan
    * @param float $amount
    * @return void
    */
    private function createUser(string $email, PricingPlan $pricingPlan, float $amount): void
    {
        $user  = User::createNew([
            'email' => $email,
            'password' => Str::random(20)
        ]);

        $mail_data = [
            'email' =>  $user->email,
            'chrome_extension_url' => config('app.chrome_extension_url'),
            'amount' => $amount,
            'pricing_plan' => $pricingPlan,
        ];

        Mail::to($user)->send(new WelcomeMail($mail_data));
        Mail::to($user)->send(new SetPasswordMail($mail_data));
    }

    /**
    * Create transaction
    *
    * @param User $user
    * @param PricingPlan $pricingPlan
    * @param float $amount
    * @param string $reference
    * @return void
    */
    private function createTransaction(User $user, PricingPlan $pricingPlan, float $amount, string $reference): void 
    {
        $data = [
            'user_id' => $user->id,
            'pricing_plan_id' => $pricingPlan->id,
            'amount' =>  $amount,
            'reference' => $reference,
            'status' => Transaction::STATUS['SUCCESS'],
            'narration' => "Payment for {$pricingPlan->name} Plan ($pricingPlan->period)"
        ];

        Transaction::createNew($data);

        $mail_data = [
            'user' => $user,
            'amount' => $amount,
            'pricing_plan' => $pricingPlan,
            'reference' => $reference,
        ];

        Mail::to($user)->send(new SuccessfulPaymentMail($mail_data));
    }

    /**
    * Update Webhook Log
    *
    * @param WebhookLog $webhookLog
    * @param string $response
    * @return JsonResponse
    */
    private function updateWebhookLog(WebhookLog $webhookLog, string $response): void
    {
        $webhookLog->update([
            'response' => $response
        ]);
    }
}