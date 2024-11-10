<?php
namespace App\Actions\ServicePayment;

use App\Models\Service;
use App\Traits\FormatApiResponse;
use Throwable;

class GetValidationFieldsAction
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
            $response = Service::getServiceClass($data['service_name'])->getUniqueRequiredFields();

            return $this->formatApiResponse(200, 'Service validation fields retrieved successfully.', $response);
            
        } catch (Throwable $th) {
            report($th);
            return $this->formatApiResponse(500, 'Unable to retrieve validation fields.');
        }  
    }
}