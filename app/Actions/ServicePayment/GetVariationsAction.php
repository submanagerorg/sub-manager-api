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
            $serviceName = strtolower($data['service_name']);
            $response = Cache::rememberForever($serviceName. '-variations', function () use ($serviceName){
                return Service::getServiceClass($serviceName)->getVariations();
            });

            $fee = config('fee.tv_service');

            $response = collect($response)->map(function ($item) use ($fee) {
                $item['fee'] =  sprintf('%.2f', $fee);
                $item['total'] = sprintf('%.2f', $item['amount'] + $fee);
                
                return $item;
            })->toArray();

            return $this->formatApiResponse(200, 'Service variations retrieved successfully.', $response);

        } catch (Throwable $th) {
            report($th);
            return $this->formatApiResponse(500, 'Unable to retrieve service variations.');
        }   
    }
}