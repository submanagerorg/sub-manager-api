<?php
namespace App\Actions\ServicePayment;

use App\Actions\ServicePayment\PayPipeline\HandlePaymentState;
use App\Actions\ServicePayment\PayPipeline\Stages\ReadPaymentResponse;
use App\Actions\ServicePayment\PayPipeline\Stages\ReverseUserDebitWhenTransactionFails;
use App\Actions\ServicePayment\PayPipeline\Stages\SendEmail;
use App\Actions\ServicePayment\PayPipeline\Stages\TrackAutoRenewal;
use App\Actions\ServicePayment\PayPipeline\Stages\TrackSubscription;
use App\Actions\ServicePayment\PayPipeline\Stages\UpdateServicePaymentRequest;
use App\Exceptions\InsufficientFundsException;
use App\Models\Currency;
use App\Models\Service;
use App\Models\ServicePaymentRequest;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Traits\FormatApiResponse;
use App\Traits\TransactionTrait;
use Exception;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class PayAction
{
    use FormatApiResponse, TransactionTrait;

   /**
    *
    * @param array $data
    * @return JsonResponse
    */
    public function execute(User $user, array $data)
    { 
        try{

            DB::beginTransaction();
            $service = Service::getServiceClass($data['service_name']);

            $fee = config('fee.tv_service');

            $reference = $this->generateReference(Wallet::LABEL);
            
            $smartCardNumber = isset($data['smartcard_number']) ? $data['smartcard_number'] : null;
            $customerDetails = $service->getSmartCardDetails($smartCardNumber);

            if($data['subscription_type'] == 'renew' && isset($customerDetails['renewal_amount'])) {
                $amount = $customerDetails['renewal_amount'];
                $data['amount'] = $amount;
            } else{
                $variations = $service->getVariations();

                $variation = array_filter($variations, function ($item) use ($data) {
                    return $item['variation_code'] == $data['variation_code'];
                });
    
                $variation = reset($variation); 

                if ($variation) {
                    $amount = $variation['amount'];
                    $data['variation_amount'] = $variation['amount'];
                } else {
                    throw new Exception("Variation not found.");
                }
            }

            $user->wallet->debit($reference, $amount, $fee, WalletTransaction::TYPE['WITHDRAW'], ucfirst($data['service_name']).' Service Payment');

            $data['request_id'] = $service->generateRequestId();
            $data = $this->fillMetadata($data, $user, $service->getCurrencyCode(), $amount + $fee);

            ServicePaymentRequest::create([
                'request_id' => $data['request_id'],
                'service_id' => Service::where('name', $data['service_name'])->first()->id,
                'request_data' => $data,
                'user_id' => $user->id,
                'wallet_transaction_id' => WalletTransaction::where('reference', $reference)->first()->id,
            ]);
 
            DB::commit();

            $response = $service->pay($data);

            $successful = $this->executeAfterPayLogic($data, $response);

            if (!$successful) {
                return $this->formatApiResponse(400, 'Service payment failed.');
            }
            
            return $this->formatApiResponse(200, 'Service payment completed successfully.');
        } catch(Throwable $th) {
            DB::rollback();

            if ($th instanceof InsufficientFundsException) {
                throw $th;
            }

            report($th);
            return $this->formatApiResponse(500, 'Unable to initiate service payment.');
        }
        
    }

    private function fillMetadata(array $data, User $user, string $currency, $fullAmount): array 
    {
        $data['user_id'] = $user->id;
        $data['service_id'] = optional(Service::getByName($data['service_name']))->id;
        $data['currency_id'] = optional(Currency::whereCode($currency)->first())->id;
        $data['full_amount'] = $fullAmount;

        return $data;
    }

    private function executeAfterPayLogic(array $data, mixed $payResponse) 
    {
        $state = new HandlePaymentState($data, $payResponse);

        Log::info("Payment response: ", [$payResponse]);

        app(Pipeline::class)->send($state)->through([
            ReadPaymentResponse::class,
            UpdateServicePaymentRequest::class,
            TrackSubscription::class,
            TrackAutoRenewal::class,
            ReverseUserDebitWhenTransactionFails::class,
            SendEmail::class
        ])->thenReturn();

        return $state->transactionSuccessful();
    }
}