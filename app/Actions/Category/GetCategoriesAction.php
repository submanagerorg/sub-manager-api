<?php
namespace App\Actions\Category;

use App\Models\Category;
use App\Models\Service;
use App\Traits\FormatApiResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use OpenAI\Laravel\Facades\OpenAI;


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

    /**
     *
     *
     */
    public function autoCategorize(string $service)
    {
        $categoryId = null;

        $categoryId = $this->checkTheCacheForAMatch($service);

        if (!$categoryId) {
            $categoryId = $this->compareStringMatch($service, 'exact');
        }

        if (!$categoryId) {
            $categoryId = $this->compareStringMatch($service, 'like');
        }

        if (!$categoryId) {
            $categoryId = $this->getSoundexMatch($service);
        }

        if (!$categoryId) {
            $categoryId = Category::whereName(Category::OTHER)->first()->id;
        }

        $this->cacheTheMatch($service, $categoryId);

        return $categoryId;
    }

    /**
     *
     */
    private function checkTheCacheForAMatch(string $service): ?int {
        $mappings = Cache::get('service-category-mapping', []);

        return $mappings[$service] ?? null;
    }

    /**
     *
     */
    private function compareStringMatch(string $service, string $matchPattern = 'exact'): ?int {
        if ($matchPattern === 'exact') {
            return optional(Service::whereName($service)->first())->category_id;
        }

        return optional(Service::where('name', 'like', "%$service%")->first())->category_id;
    }

    /**
     *
     */
    private function getSoundexMatch(string $service): ?int {
        return optional(DB::table('services')->whereRaw("SOUNDEX(name) = SOUNDEX('$service')")->first())->category_id;
    }

    /**
     *
     */
    private function cacheTheMatch(string $service, int $categoryId): void {
        $mappings = Cache::pull('service-category-mapping');
        $mappings[$service] = $categoryId;

        Cache::put('service-category-mapping', $mappings);
    }
}
