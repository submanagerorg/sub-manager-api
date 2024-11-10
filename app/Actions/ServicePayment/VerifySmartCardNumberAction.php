<?php
namespace App\Actions\ServicePayment;

use App\Models\Service;
use App\Traits\FormatApiResponse;
use Throwable;

class VerifySmartCardNumberAction
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
            $response = Service::getServiceClass($data['service_name'])->getSmartCardDetails();

            if(!$response) {
                return $this->formatApiResponse(400, 'Smart card number cannot be verified.');
            }

            return $this->formatApiResponse(200, 'Smart card number verified successfully.', $response);
            
        } catch (Throwable $th) {
            report($th);
            return $this->formatApiResponse(500, 'Unable to verify smart card number.');
        }    
    }
}