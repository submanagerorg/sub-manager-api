<?php
namespace App\Actions\Currency;

use App\Models\Currency;
use App\Traits\FormatApiResponse;


class GetCurrenciesAction
{
    use FormatApiResponse;

   /**
    *
    * @param array $data
    * @return JsonResponse
    */
    public function execute(array $data)
    {
        $currencies = Currency::where('is_active', 1)->orderBy('name', 'asc')->get();

        return $this->formatApiResponse(200, 'Currencies retrieved successfuly', $currencies);
    }
}