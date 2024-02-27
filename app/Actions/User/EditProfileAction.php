<?php
namespace App\Actions\User;

use App\Models\Timezone;
use App\Models\User;
use App\Traits\FormatApiResponse;


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

        return $this->formatApiResponse(200, 'User profile has been updated', ['user' => $user]);
    }
}