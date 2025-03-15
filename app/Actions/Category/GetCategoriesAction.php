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
        $serviceId = null;
        $categoryId = null;

        [$serviceId, $categoryId] = $this->checkTheCacheForAMatch($service);

        if (!$categoryId) {
            [$serviceId, $categoryId] = $this->compareStringMatch($service, 'exact');
        }

        if (!$categoryId) {
            [$serviceId, $categoryId] = $this->compareStringMatch($service, 'like');
        }

        if (!$categoryId) {
            [$serviceId, $categoryId] = $this->getSoundexMatch($service);
        }

        if (!$categoryId) {
            $categoryId = Category::whereName(Category::OTHER)->first()->id;
        }

        $this->cacheTheMatch($service, $serviceId, $categoryId);

        return $categoryId;
    }

    /**
     *
     */
    private function checkTheCacheForAMatch(string $service): array {
        $mappings = Cache::get('service-category-mapping', []);

        return $mappings[$service] ?? [null, null];
    }

    /**
     *
     */
    private function compareStringMatch(string $service, string $matchPattern = 'exact'): array {
        if ($matchPattern === 'exact') {
            $service = Service::whereName($service)->first();

            return $service ? [$service->id, $service->category_id] : [null, null];
        }

        $service = Service::where('name', 'like', "%$service%")->first();

        return $service ? [$service->id, $service->category_id] : [null, null];
    }

    /**
     *
     */
    private function getSoundexMatch(string $service): array {
        $service = DB::table('services')->whereRaw("SOUNDEX(name) = SOUNDEX('$service')")->first();

        return $service ? [$service->id, $service->category_id] : [null, null];
    }

    /**
     *
     */
    private function cacheTheMatch(string $service, ?int $serviceId, int $categoryId): void {
        $mappings = Cache::pull('service-category-mapping');
        $mappings[$service] = [$serviceId, $categoryId];

        Cache::put('service-category-mapping', $mappings);
    }
}
