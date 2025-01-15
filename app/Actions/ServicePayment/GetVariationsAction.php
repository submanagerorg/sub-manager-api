<?php
namespace App\Actions\ServicePayment;

use App\Models\Service;
use App\Traits\FormatApiResponse;
use Illuminate\Support\Facades\Cache;
use Throwable;

class GetVariationsAction
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
            $response = Cache::rememberForever($data['service_name']. '-variations', function () use ($data){
                return Service::getServiceClass($data['service_name'])->getVariations();
            });

            return $this->formatApiResponse(200, 'Service variations retrieved successfully.', $response);

        } catch (Throwable $th) {
            report($th);
            return $this->formatApiResponse(500, 'Unable to retrieve service variations.');
        }   
    }
}