<?php 

namespace App\Drivers;

use App\Interfaces\PayForServiceInterface;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class VTPassServiceDriver implements PayForServiceInterface
{
    public function payTV(array $paymentData, string $service): mixed
    {
        try {

            if (!isset(
                $paymentData['card_number'], 
                $paymentData['variation_code'], 
                $paymentData['phone_number'])) {
                throw new Exception('The data passed for dstv payment is incomplete');
            }

            $url = '/pay';
            $data = [
                'request_id' => $this->generateRequestId(),
                'serviceID' => $service,
                'billersCode' => $paymentData['card_number'],
                'variation_code' => $paymentData['variation_code'],
                'phone' => $paymentData['phone_number'],
                'subscription_type' => 'change'
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

    public function getVariations(string $service): mixed 
    {
        try {

            $url = '/service-variations';
            $query = [
                'serviceID' => $service
            ];

            $response = $this->getBasic($url, $query);

            return $response->json();

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

    public function getSmartCardDetails(string|int $cardNumber, string $service): mixed 
    {
        try {

            $url = '/merchant-verify';
            $data = [
                'billersCode' => $cardNumber,
                'serviceID' => $service
            ];

            $response = $this->postBasic($url, $data);

            return $response->json();

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

    private function generateRequestId(): string 
    {
        $now = now();
        $now->setTimezone('Africa/Lagos');

        return $now->format('YmdHi') . config('vtpass.salt');
    }
}