<?php
namespace App\PaymentProviders;
 
use App\Traits\FormatApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class Paystack
{
    use FormatApiResponse;

    public $base_url;
    public $secret_key;
    public $public_key;

    /**
     * Initialize a new paystack instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->base_url = config('paystack.base_url');
        $this->secret_key = config('paystack.secret_key');
        $this->public_key = config('paystack.public_key');
    }

   /**
    * Initialize payment 
    *
    * @param array $data
    * @return array
    */
    public function initiatePayment(array $data): array
    {
        $url = "{$this->base_url}/transaction/initialize";

        $requestData = [
            "currency" => $data['currency'],
            'amount' => $data['plan']->amount * 100, //amount is converted to kobo
            'email' => $data['email'],
            'reference' => $data['reference'],
            'metadata' => [
                'pricing_plan_uid' =>  $data['plan']->uid,
            ],
            'callback_url' => config('app.website_url') . "/payment/callback?planuid={$data['plan']->uid}&email={$data['email']}"
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer {$this->secret_key}",
        ])->post(
            $url, $requestData
        )->object();

        if($response->status !== true){
            logger("Paystack Error - " . json_encode($response));

            $errorMessage = isset($response->message) ? $response->message : null;
            return PaymentProvider::errorResponse($errorMessage);
        }

        return PaymentProvider::initiatePaymentResponse($response->data->authorization_url, $response->data->reference);
    }

    /**
    * Verify Payment
    *
    * @param string $reference
    * @return array
    */
    public function verifyPayment(string $reference): array
    {
        $url = "{$this->base_url}/transaction/verify/{$reference}";

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->secret_key}",
        ])->get(
            $url
        )->object();

        if($response->status !== true){
            logger("Paystack Error - " . json_encode($response));
            
            $errorMessage = isset($response->message) ? $response->message : null;
            return PaymentProvider::errorResponse($errorMessage);
        }

        return PaymentProvider::verifyPaymentResponse(
            $response->data->status, $response->data->reference, $response->data->amount / 100, 
            $response->data->customer->email, $response->data->metadata->pricing_plan_uid
        );
    }

    /**
    * Validate Webhook
    *
    * @param Request $request
    * @return array
    */
    public function validateWebhook(Request $request): array
    {   
        $data = json_encode(json_decode($request->getContent()));

        if($request->header('x-paystack-signature') !== hash_hmac('sha512', $data, $this->secret_key)) {
            $errorMessage = 'Failed to validate webhook';
            return PaymentProvider::errorResponse($errorMessage);
        }

        return PaymentProvider::validateWebhookResponse($request['data']['reference']);
    }
}