<?php
namespace App\Actions\Category;

use App\Models\Category;
use App\Traits\FormatApiResponse;


class GetCategoriesAction
{
    use FormatApiResponse;

   /**
    *
    * @param array $data
    * @return JsonResponse
    */
    public function execute(array $data)
    {
        $categories = Category::orderBy('name', 'asc')->get();

        return $this->formatApiResponse(200, 'Categories retrieved successfuly', $categories);
    }
}