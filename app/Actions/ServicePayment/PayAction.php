<?php
namespace App\Actions\ServicePayment;

use App\Exceptions\InsufficientFundsException;
use App\Models\Service;
use App\Models\ServicePaymentRequest;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Traits\FormatApiResponse;
use App\Traits\TransactionTrait;
use Exception;
use Illuminate\Support\Facades\DB;
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
        DB::beginTransaction();
        try{

            $service = Service::getServiceClass($data['service_name']);

            $fee = 0;

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
                    $amount = $variation['variation_amount'];
                    $data['variation_amount'] = $variation['variation_amount'];
                } else {
                    throw new Exception("Variation not found.");
                }
            }

            $user->wallet->debit($reference, $amount, $fee, WalletTransaction::TYPE['WITHDRAW'], ucfirst($data['service_name']).' Service Payment');

            $data['request_id'] = $service->generateRequestId();

            ServicePaymentRequest::create([
                'request_id' => $data['request_id'],
                'service_id' => Service::where('name', $data['service_name'])->first()->id,
                'request_data' => $data,
                'user_id' => $user->id,
                'wallet_transaction_id' => WalletTransaction::where('reference', $reference)->first()->id,
            ]);
 
            DB::commit();

            app()->terminating(function () use ($service, $data) {
                $service->pay($data);
            });
            
            return $this->formatApiResponse(200, 'Service payment initiated successfully.');
        } catch(Throwable $th) {
            DB::rollback();

            if ($th instanceof InsufficientFundsException) {
                return response()->json([
                    'status' => 400,
                    'message' => $th->getMessage(),
                ], 400);
            }

            report($th);
            return $this->formatApiResponse(500, 'Unable to initiate service payment.');
        }
        
    }
}