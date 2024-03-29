<?php

namespace App\Http\Controllers;

use App\Actions\Subscription\AddSubscriptionAction;
use App\Actions\Subscription\EditSubscriptionAction;
use App\Actions\Subscription\GetSubscriptionAction;
use App\Actions\Subscription\GetSubscriptionsAction;
use App\Actions\Subscription\RemoveSubscriptionAction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Subscription\AddSubscriptionRequest;
use App\Http\Requests\Subscription\EditSubscriptionRequest;

class SubscriptionController extends Controller
{
      
    public function addSubscription(AddSubscriptionRequest $request)
    {
        return (new AddSubscriptionAction())->execute($request->validated());
    }

    public function getSubscription($subscriptionId)
    {
        return (new GetSubscriptionAction())->execute($subscriptionId);
    }

    public function getSubscriptions(Request $request)
    {
        return (new GetSubscriptionsAction())->execute($request->all());
    }

    public function removeSubscription($subscriptionId)
    {
        return (new RemoveSubscriptionAction())->execute($subscriptionId);
    }

    public function editSubscription($subscriptionId, EditSubscriptionRequest $request)
    {
        return (new EditSubscriptionAction())->execute($subscriptionId, $request->validated());
    }
    
}
