<?php 

namespace App\Actions\ServicePayment\PayPipeline\Stages;

use App\Actions\ServicePayment\PayPipeline\HandlePaymentState;
use App\Parsers\PayResponseParser;
use Closure;
use Illuminate\Support\Facades\Log;

class ReadPaymentResponse 
{
    public function handle(HandlePaymentState $state, Closure $next)
    {
        $payResponse = $state->getPayResponse();
        $parser = new PayResponseParser($payResponse);
        $response = $parser->parse();
        $state->setParsedPayResponse($response);

        Log::info("Payment response parsed successfully");
        return $next($state);   
    }
}