<?php
namespace App\Actions\Service;

use App\Models\Service;
use App\Traits\FormatApiResponse;


class GetServicesAction
{
    use FormatApiResponse;

   /**
    *
    * @param array $data
    * @return JsonResponse
    */
    public function execute(array $data)
    {

        $services = Service::query();
        
        if (isset($data['status'])) {
            $services->where('status', $data['status']);
        }
        
        $services->orderBy('name', 'asc')->get();

        return $this->formatApiResponse(200, 'Services retrieved successfuly', $services->get());
    }
}