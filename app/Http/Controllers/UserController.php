<?php

namespace App\Http\Controllers;

use App\Actions\User\EditProfileAction;
use App\Http\Requests\User\EditProfileRequest;

class UserController extends Controller
{
      
    public function editProfile(EditProfileRequest $request)
    {
        return (new EditProfileAction())->execute($request->validated());
    }
}
