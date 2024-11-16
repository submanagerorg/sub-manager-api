<?php 

namespace App\Drivers;

use App\Interfaces\PayForServiceInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class VTPassServiceDriver implements PayForServiceInterface
{
    public function payTV(array $paymentData, string $service): mixed
    {
        try {

            if (!isset(
                $paymentData['smartcard_number'], 
                $paymentData['variation_code'], 
                $paymentData['phone_number'])
                ) {
                throw new Exception("The data passed for {$service} payment is incomplete");
            }

            $url = '/pay';
            $data = [
                'request_id' => $paymentData['request_id'] ?? $this->generateRequestId(),
                'serviceID' => $service,
                'billersCode' => $paymentData['smartcard_number'],
                'variation_code' => $paymentData['variation_code'],
                'phone' => $paymentData['phone_number'],
                'subscription_type' => $paymentData['subscription_type'] ?? 'change'
            ];

            // This useful for renewals. The price is gotten from the smartcard verification endpoint.
            // The price maybe be reduced for a renewal as opposed to a fresh sub
            if (isset($paymentData['amount'])) {
                $data['amount'] = $paymentData['amount'];
            }

            $response = $this->postBasic($url, $data);

            return $response->json();

        } catch (Throwable $e) {
            Log::error($e->getMessage(), [$e->getTraceAsString()]);
            throw $e;
        }
    }

    public function payDstv(array $paymentData): mixed
    {
        return $this->payTV($paymentData, 'dstv');
    }

    public function payGotv(array $paymentData): mixed 
    {
        return $this->payTV($paymentData, 'gotv');
    }

    public function payStartimes(array $paymentData): mixed 
    {
        return $this->payTV($paymentData, 'startimes');
    }

    public function payShowmax(array $paymentData): mixed 
    {
        try {

            if (!isset(
                $paymentData['variation_code'], 
                $paymentData['phone_number'])) {
                throw new Exception('The data passed for showmax payment is incomplete');
            }

            $url = '/pay';
            $data = [
                'request_id' => $paymentData['request_id'] ?? $this->generateRequestId(),
                'serviceID' => 'showmax',
                'billersCode' => $paymentData['phone_number'],
                'variation_code' => $paymentData['variation_code'],
                'phone' => $paymentData['phone_number']
            ];

            // This useful for renewals. The price is gotten from the smartcard verification endpoint.
            // The price maybe be reduced for a renewal as opposed to a fresh sub
            if (isset($paymentData['amount'])) {
                $data['amount'] = $paymentData['amount'];
            }

            $response = $this->postBasic($url, $data);

            return $response->json();

        } catch (Throwable $e) {
            Log::error($e->getMessage(), [$e->getTraceAsString()]);
            throw $e;
        }
    }

    public function getVariations(string $service): mixed 
    {
        try {

            $url = '/service-variations';
            $query = [
                'serviceID' => $service
            ];

            $response = $this->getBasic($url, $query);

            $responseBody = $response->json();
            $variations = $responseBody['content']['varations'];

            return collect($variations)->map(function ($item) {
                return [
                    'variation_code' => $item['variation_code'],
                    'name' => $item['name'],
                    'amount' => $item['variation_amount']
                ];
            })->toArray();

        } catch (Throwable $e) {
            Log::error($e->getMessage(), [$e->getTraceAsString()]);
            throw $e;
        }
    }

    public function getDstvVariations(): mixed 
    {
        return $this->getVariations('dstv');
    }

    public function getGotvVariations(): mixed 
    {
        return $this->getVariations('gotv');
    }

    public function getStartimesVariations(): mixed 
    {
        return $this->getVariations('startimes');
    }

    public function getShowmaxVariations(): mixed 
    {
        return $this->getVariations('showmax');
    }

    public function getSmartCardDetails(string|int $cardNumber, string $service): mixed 
    {
        try {

            $url = '/merchant-verify';
            $data = [
                'billersCode' => $cardNumber,
                'serviceID' => $service
            ];

            $response = $this->postBasic($url, $data);
            $responseBody = $response->json();

            return [
                'customer_name' => $responseBody['content']['Customer_Name'],
                'due_date' =>  isset($responseBody['content']['Due_Date']) ? Carbon::parse($responseBody['content']['Due_Date'])->toDateString() : null,
                'current_variation_name' => isset($responseBody['content']['Current_Bouquet']) ? $responseBody['content']['Current_Bouquet'] : null,
                'current_variation_code' => isset($responseBody['content']['Current_Bouquet_Code']) ? $responseBody['content']['Current_Bouquet_Code'] : null,
                'renewal_amount' => isset($responseBody['content']['Renewal_Amount']) ? $responseBody['content']['Renewal_Amount'] : null
            ];

        } catch (Throwable $e) {
            Log::error($e->getMessage(), [$e->getTraceAsString()]);
            throw $e;
        }
    }

    public function getDstvSmartCardDetails(string|int $cardNumber): mixed 
    {
        return $this->getSmartCardDetails($cardNumber, 'dstv');
    }

    public function getGotvSmartCardDetails(string|int $cardNumber): mixed 
    {
        return $this->getSmartCardDetails($cardNumber, 'gotv');
    }

    public function getStartimesSmartCardDetails(string|int $cardNumber): mixed 
    {
        return $this->getSmartCardDetails($cardNumber, 'startimes');
    }

    public function getWalletBalance(): mixed
    {
        try {

            $url = '/balance';

            $response = $this->get($url);        

            return $response->json();

        } catch (Throwable $e) {
            Log::error($e->getMessage(), [$e->getTraceAsString()]);
            throw $e;
        }
    }

    public function getTransactionStatus(string $requestId):mixed 
    {
        try {

            $url = '/requery';

            $response = $this->postBasic($url, [
                'request_id' => $requestId
            ]);

            return $response->json();

        } catch (Throwable $e) {
            Log::error($e->getMessage(), [$e->getTraceAsString()]);
            throw $e;
        }
    }

    private function get(string $url, array $query = []): Response 
    {
        return Http::withHeaders([
            'api-key' => config('vtpass.api_key'),
            'public-key' => config('vtpass.public_key')
        ])->get(config('vtpass.url') . $url, $query);
    }

    private function post(string $url, array $data = []): Response
    {
        return Http::withHeaders([
            'api-key' => config('vtpass.api_key'),
            'secret-key' => config('vtpass.secret_key')
        ])->post(config('vtpass.url') . $url, $data);
    }

    private function getBasic(string $url, array $query = []): Response
    {
        $username = config('vtpass.username');
        $password = config('vtpass.password');

        $credentials = base64_encode("{$username}:{$password}");

        return Http::withHeaders([
            'api-key' => config('vtpass.api_key'),
            'public-key' => config('vtpass.public_key'),
            'Authorization' => "Basic {$credentials}"
        ])->get(config('vtpass.url') . $url, $query);
    }

    private function postBasic(string $url, array $data = []): Response
    {
        $username = config('vtpass.username');
        $password = config('vtpass.password');

        $credentials = base64_encode("{$username}:{$password}");

        return Http::withHeaders([
            'api-key' => config('vtpass.api_key'),
            'public-key' => config('vtpass.public_key'),
            'Authorization' => "Basic {$credentials}"
        ])->post(config('vtpass.url') . $url, $data);
    }

    public function generateRequestId(): string 
    {
        $now = now();
        $now->setTimezone('Africa/Lagos');

        return $now->format('YmdHi') . Str::random(5) . config('vtpass.salt');
    }
}