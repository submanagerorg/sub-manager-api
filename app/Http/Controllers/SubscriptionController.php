<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        
    }

    public function saveSubscription(Request $request){
        $validator = Validator::make($request->all(),
        [
            'name' => 'string|required',
            'description' => 'text|nullable',
            'expiry_date' => 'datetime|required',
            'reminder_frequency' => 'integer|nullable'
        ]);

        if($validator->fails()){
            return $this->formatApiResponse(422, 'validator failed', [], $validator->errors());
        }

        $subscription = Subscription::createSubscription();

        if(!$subscription){
            return $this->formatApiResponse(500, 'Subscription not saved');
        }

        return $this->formatApiResponse(201, 'Subscription saved successfully', $subscription);
    }
}
