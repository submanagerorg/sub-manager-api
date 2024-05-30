<?php

namespace App\Http\Controllers;

use App\Actions\Subscription\AddSubscriptionAction;
use App\Actions\Subscription\EditSubscriptionAction;
use App\Actions\Subscription\GetSubscriptionAction;
use App\Actions\Subscription\GetSubscriptionsAction;
use App\Actions\Subscription\RemoveSubscriptionAction;
use App\Actions\Subscription\RenewSubscriptionAction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Filters\SubscriptionFilter;
use App\Http\Requests\Subscription\AddSubscriptionRequest;
use App\Http\Requests\Subscription\EditSubscriptionRequest;
use App\Http\Requests\Subscription\RenewSubscriptionRequest;
use Illuminate\Http\JsonResponse;

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

    public function getSubscriptions(SubscriptionFilter $filter, Request $request)
    {
        return (new GetSubscriptionsAction())->execute($filter, $request->all());
    }

    public function removeSubscription($subscriptionId)
    {
        return (new RemoveSubscriptionAction())->execute($subscriptionId);
    }

    public function editSubscription($subscriptionId, EditSubscriptionRequest $request)
    {
        return (new EditSubscriptionAction())->execute($subscriptionId, $request->validated());
    }

    public function renewSubscription(string $parentId, RenewSubscriptionRequest $request): JsonResponse
    {
        return (new RenewSubscriptionAction())->execute($parentId, $request->validated());
    }
    
}
