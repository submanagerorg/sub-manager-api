<?php

namespace App\Http\Controllers;

use App\Actions\User\EditProfileAction;
use App\Actions\User\GetProfileAction;
use App\Http\Requests\User\EditProfileRequest;

class UserController extends Controller
{
      
    public function editProfile(EditProfileRequest $request)
    {
        return (new EditProfileAction())->execute($request->validated());
    }

    public function getProfile()
    {
        return (new GetProfileAction())->execute();
    }
}
