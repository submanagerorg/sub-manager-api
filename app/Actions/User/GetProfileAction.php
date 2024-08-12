<?php
namespace App\Actions\User;

use App\Traits\FormatApiResponse;
use Illuminate\Http\JsonResponse;
use Throwable;

class GetProfileAction
{
    use FormatApiResponse;
    
   /**
    * Get user profile
    *
    * @return JsonResponse
    */
    public function execute(): JsonResponse
    {
        try{
            
            $user = auth()->user();
    
            return $this->formatApiResponse(200, 'User profile retrieved successfully', ['user' => $user]);

        } catch(Throwable $e) {
            report($e);
            return $this->formatApiResponse(500, 'Error occured', [], $e->getMessage());
        }
       
    }
}