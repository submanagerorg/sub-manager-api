<?php
namespace App\Actions\User;

use App\Models\Timezone;
use App\Models\User;
use App\Traits\FormatApiResponse;
use Throwable;

class EditProfileAction
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
            
            $user = auth()->user();

            if(isset($data['username'])){
                if ($user->username != $data['username'] && User::where('username', $data['username'])->exists()){
                    return $this->formatApiResponse(400, 'Username is already taken');
                }
    
                $user->update([
                    'username' => $data['username']
                ]);
            }
    
            if(isset($data['timezone'])){
                $timezone = Timezone::where('zone_name', $data['timezone'])->first();
    
                $user->update([
                    'timezone_id' => $timezone->id
                ]);
            }
    
            $user->refresh();
    
            return $this->formatApiResponse(200, 'User profile has been updated', ['user' => $user]);

        } catch(Throwable $e) {
            report($e);
            return $this->formatApiResponse(500, 'Error occured', [], $e->getMessage());
        }
       
    }
}