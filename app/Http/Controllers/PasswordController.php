<?php

namespace App\Http\Controllers;

use App\Http\Requests\Password\ChangePasswordRequest;
use App\Http\Requests\Password\ForgotPasswordRequest;
use App\Http\Requests\Password\ResetPasswordRequest;
use App\Mail\ChangePasswordMail;
use App\Mail\ResetPasswordMail;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();

            if(!$user){
                return $this->formatApiResponse(400, 'User does not exist');
            }

            $mail_data = [
                'user' => $user,
                'user_agent' => $request->header('User-Agent')
            ];

          
            Mail::to($user->email)->send(new ResetPasswordMail($mail_data));

        } catch (\Exception $exception) {
            logger($exception);
            return $this->formatApiResponse(500, 'Error occured', [], $exception->getMessage());
        }

        return $this->formatApiResponse(200, 'Password reset link has been sent to the provided email address');
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $token = PasswordReset::where('token', $request->token)->first();

            if (!$token) {
                return $this->formatApiResponse(400, 'Invalid token');
            }

            $user = User::where('email', $token->email)->first();

            if (!$user) {
                return $this->formatApiResponse(400, 'User does not exist');
            }

            if (now()->gt(Carbon::parse($token->created_at)->addMinutes(15))) {
                return $this->formatApiResponse(400, 'Expired Token');
            }

            $user->password = bcrypt($request->password);
            $user->save();

            $token->delete();

            Mail::to($user->email)->send(new ChangePasswordMail());

        } catch (\Exception $exception) {
            logger($exception);
            return $this->formatApiResponse(500, 'Error occured', [], $exception->getMessage());
        }

        return $this->formatApiResponse(200, 'Password successfully reset');
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            $user = auth()->user();

            if (!Hash::check($request->old_password, $user->password)) {
                return $this->formatApiResponse(400, 'Old password is incorrect');
            }

            $user->password = bcrypt($request->password);
            $user->save();

            Mail::to($user->email)->send(new ChangePasswordMail());

        } catch (\Exception $exception) {
            logger($exception);
            return $this->formatApiResponse(500, 'Error occured', [], $exception->getMessage());
        }
        
        return $this->formatApiResponse(200, 'Password successfully updated');
    }
}
