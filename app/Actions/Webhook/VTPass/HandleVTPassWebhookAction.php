<?php 

namespace App\Actions\Webhook\VTPass;

use App\Actions\Webhook\VTPass\Stages\LogWebhookRequest;
use App\Actions\Webhook\VTPass\Stages\ReadRequestData;
use App\Actions\Webhook\VTPass\Stages\RetrieveMetadata;
use App\Actions\Webhook\VTPass\Stages\ReverseUserDebitWhenTransactionFails;
use App\Actions\Webhook\VTPass\Stages\SendEmail;
use App\Actions\Webhook\VTPass\Stages\TrackAutoRenewal;
use App\Actions\Webhook\VTPass\Stages\TrackSubscription;
use App\Actions\Webhook\VTPass\Stages\UpdateCacheForVariations;
use App\Actions\Webhook\VTPass\Stages\UpdateServicePaymentRequest;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class HandleVTPassWebhookAction
{
    public function execute(Request $request)
    {
        $state = new HandleVTPassWebhookState($request);

        (new Pipeline())->send($state)->through([
            LogWebhookRequest::class,
            ReadRequestData::class,
            UpdateServicePaymentRequest::class,
            UpdateCacheForVariations::class,
            RetrieveMetadata::class,
            TrackSubscription::class,
            TrackAutoRenewal::class,
            ReverseUserDebitWhenTransactionFails::class,
            SendEmail::class
        ]);
    }
}