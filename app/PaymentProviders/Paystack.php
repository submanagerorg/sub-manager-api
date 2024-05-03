<?php
namespace App\PaymentProviders;
 
use App\Traits\FormatApiResponse;
use Illuminate\Support\Facades\Http;
use Throwable;

class Paystack
{
    use FormatApiResponse;

    public $base_url;
    public $secret_key;
    public $public_key;

    /**
     * Initialize a new filter instance.
     *
     * @param Request $request
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
    * @return JsonResponse
    */
    public function initiatePayment(array $data)
    {
        $url = "{$this->base_url}/transaction/initialize";

        $requestData = [
            "currency" => $data['currency'],
            'amount' => $data['plan']->amount * 100, //amount is converted to kobo
            'email' => $data['email'],
            'reference' => $data['reference'],
            'metadata' => [
                'pricing_plan_uid' =>  $data['plan']->uid,
            ]
        ];

        logger($requestData);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer {$this->secret_key}",
        ])->post(
            $url, $requestData
        )->object();

        logger(json_encode($response));
        // dd($response);
        return $response;
    }

    /**
    *Verify Payment
    *
    * @param array $data
    * @return JsonResponse
    */
    public function verifyPayment(string $reference)
    {
        $url = "{$this->base_url}/transaction/verify/{$reference}";

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->secret_key}",
        ])->get(
            $url
        )->object();

        logger($response);
        dd($response);
    }
}