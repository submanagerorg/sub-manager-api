<?php
namespace App\Actions\Category;

use App\Models\Category;
use App\Traits\FormatApiResponse;
use Illuminate\Http\Response;
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
        $allAvailableVCategories = Category::get();

        $result = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                'role' => 'user',
                'content' => "you are an auto categorization engine with knowledge about online services,
                which of these categories does the service $service most likely belongs to [" . $allAvailableVCategories->join(', ') ."]"
            ]
        ]);

        $allResults = [];

        foreach ($result->choices as $choice) {
            $allResults[] = $choice->message->content;
        }

        return $this->formatApiResponse(Response::HTTP_OK, 'Auto categorization retrieved successfuly', $allResults);
    }
}
