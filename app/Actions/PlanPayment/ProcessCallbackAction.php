<?php
namespace App\Actions\PlanPayment;

use App\Traits\FormatApiResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProcessCallbackAction
{
    use FormatApiResponse;

   /**
    * Process Callback
    *
    * @param Request $request
    * @return RedirectResponse
    */
    public function execute(Request $request): RedirectResponse
    {
        $pricingPlanUid = $request->planUid;
        $email = $request->email;
        $reference = $request->reference;

        $url = config('app.website_url') ."/payment/callback?planuid=$pricingPlanUid&email=$email&reference=$reference";

        return redirect()->to($url);
        
    }
}