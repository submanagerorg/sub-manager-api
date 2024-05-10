<?php
namespace App\Actions\Timezone;

use App\Models\Timezone;
use App\Traits\FormatApiResponse;


class GetTimezonesAction
{
    use FormatApiResponse;

   /**
    *
    * @param array $data
    * @return JsonResponse
    */
    public function execute(array $data)
    {
        $timezones = Timezone::orderBy('zone_name', 'asc')->get();

        return $this->formatApiResponse(200, 'TImezones retrieved successfuly', $timezones);
    }
}