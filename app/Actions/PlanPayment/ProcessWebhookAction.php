<?php

namespace App\Actions\PlanPayment;

use App\Actions\Subscription\AddSubscriptionAction;
use App\Actions\Subscription\RenewSubscriptionAction;
use App\Mail\SetPasswordMail;
use App\Mail\SuccessfulPaymentMail;
use App\Mail\WelcomeMail;
use App\Models\PricingPlan;
use App\Models\Service;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Models\WebhookLog;
use App\Notifications\NewSignUpNotification;
use App\Traits\FormatApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Throwable;
use Illuminate\Support\Str;

class ProcessWebhookAction
{
    use FormatApiResponse;

    protected $user;

    /**
     * Process Webhook
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function execute(Request $request): JsonResponse
    {
        $webhookLog = WebhookLog::create([
            'request' => json_encode([
                'body' => $request->all(),
                'headers' => $request->header(),
            ], JSON_FORCE_OBJECT)
        ]);

        $paymentProvider = Transaction::getPaymentProvider();

        DB::beginTransaction();

        try {

            $paymentProvider = (new $paymentProvider());
            $validationResponse = $paymentProvider->validateWebhook($request);

            if ($validationResponse['status'] !== true) {
                $this->updateWebhookLog($webhookLog, 'Request could not be validated.');

                DB::commit();
                return $this->formatApiResponse(200, 'Request could not be validated.');
            }

            $verifyPayment = $paymentProvider->verifyPayment($validationResponse['reference']);

            if ($verifyPayment['status'] !== true) {
                $this->updateWebhookLog($webhookLog, 'Transaction could not be verified.');

                DB::commit();
                return $this->formatApiResponse(200, 'Transaction could not be verified.');
            }

            if ($verifyPayment['transaction_status'] !== 'success') {
                $this->updateWebhookLog($webhookLog, 'OK - Transaction is not successful');

                DB::commit();
                return $this->formatApiResponse(200, 'OK');
            }

            $transaction = Transaction::where('reference', $verifyPayment['reference'])->first();
            $walletTransaction =  WalletTransaction::where('reference', $verifyPayment['reference'])->first();

            if ($transaction || $walletTransaction) {
                $this->updateWebhookLog($webhookLog, 'OK - Transaction already exists');

                DB::commit();
                return $this->formatApiResponse(200, 'OK');
            }


            if ($verifyPayment['metadata']->type == 'wallet') {
                $this->processWalletTransaction($verifyPayment);
            } else {
                $pricingPlan = PricingPlan::where('uid', $verifyPayment['metadata']->pricing_plan_uid)->first();

                if (!$pricingPlan) {
                    $this->updateWebhookLog($webhookLog, 'Pricing plan does not exist');

                    DB::commit();
                    return $this->formatApiResponse(200, 'Pricing plan does not exist');
                }

                $this->processSubscription($pricingPlan, $verifyPayment);
            }

            $this->updateWebhookLog($webhookLog, 'OK');

            DB::commit();
            return $this->formatApiResponse(200, 'OK');
        } catch (Throwable $e) {
            DB::rollback();

            report($e);
            return $this->formatApiResponse(500, 'Error occured', [], $e->getMessage());
        }
    }


    /**
     * Create User 
     *
     * @param string $email
     * @param PricingPlan $pricingPlan
     * @param float $amount
     * @return void
     */
    private function createUser(string $email, PricingPlan $pricingPlan, float $amount): void
    {
        $user  = User::createNew([
            'email' => $email,
            'password' => Str::random(20)
        ]);

        $mail_data = [
            'email' =>  $user->email,
            'amount' => $amount,
            'pricing_plan' => $pricingPlan,
        ];

        Mail::to($user)->send(new WelcomeMail());
        Mail::to($user)->send(new SetPasswordMail($mail_data));

        try {
            Notification::route('slack', config('services.slack.webhook_url.info'))
                ->notify(new NewSignUpNotification(['email' => $user->email]));
        } catch (\Throwable $e) {
            Log::error('Failed to send notification to slack: ' . $e->getMessage());
        }
    }

    /**
     * Create transaction
     *
     * @param PricingPlan $pricingPlan
     * @param float $amount
     * @param string $reference
     * @return void
     */
    private function createTransaction(PricingPlan $pricingPlan, float $amount, string $reference): void
    {
        $data = [
            'user_id' => $this->user->id,
            'pricing_plan_id' => $pricingPlan->id,
            'amount' =>  $amount,
            'reference' => $reference,
            'status' => Transaction::STATUS['SUCCESS'],
            'narration' => "Payment for {$pricingPlan->name} Plan ($pricingPlan->period)"
        ];

        Transaction::createNew($data);

        $mail_data = [
            'user' => $this->user,
            'amount' => $amount,
            'pricing_plan' => $pricingPlan,
            'reference' => $reference,
        ];

        Mail::to($this->user)->send(new SuccessfulPaymentMail($mail_data));
    }

    /**
     * Add subscription
     *
     * @param PricingPlan $pricingPlan
     * @param float $amount
     * @return void
     */
    private function addSubscription(PricingPlan $pricingPlan, float $amount): void
    {
        $parentSubscription = Subscription::where('user_id', $this->user->id)->where('name', Service::DEFAULT_SERVICE)->first();

        $data = [
            'name' => Service::DEFAULT_SERVICE,
            'email' => $this->user->email,
            'currency' => Transaction::DEFAULT_CURRENCY['CODE'],
            'amount' => $amount,
            'start_date' => $this->user->userPricingPlan->start_date,
            'end_date' => $this->user->userPricingPlan->end_date,
            'description' => "{$pricingPlan->name} Plan Subscription ({$pricingPlan->period})"
        ];

        if ($parentSubscription) {
            (new RenewSubscriptionAction)->execute($parentSubscription->uid, $data, true);
        } else {
            (new AddSubscriptionAction)->execute($data, true);
        }
    }

    /**
     * Update Webhook Log
     *
     * @param WebhookLog $webhookLog
     * @param string $response
     * @return JsonResponse
     */
    private function updateWebhookLog(WebhookLog $webhookLog, string $response): void
    {
        $webhookLog->update([
            'response' => $response
        ]);
    }

    /**
     * Process Subscription
     *
     * @param PricingPlan $pricingPlan
     * @param array $verifyPayment
     * 
     * @return void
     */
    private function processSubscription(PricingPlan $pricingPlan, array $verifyPayment)
    {
        $this->user = User::where('email', $verifyPayment['email'])->first();

        if (!$this->user) {
            $this->createUser($verifyPayment['email'], $pricingPlan, $verifyPayment['amount']);

            $this->user = User::where('email', $verifyPayment['email'])->first();
        }

        $this->user->addUserPricingPlan($pricingPlan);

        $this->createTransaction($pricingPlan, $verifyPayment['amount'], $verifyPayment['reference']);

        $this->addSubscription($pricingPlan, $verifyPayment['amount']);
    }

    /**
     * Process Wallet Transaction
     *
     * @param array $verifyPayment
     * 
     * @return void
     */
    private function processWalletTransaction(array $verifyPayment)
    {
        $this->user = User::where('email', $verifyPayment['email'])->first();
        $fee = $verifyPayment['metadata']->fee;
        $amount = $verifyPayment['amount'] - $fee;

        $this->user->wallet->credit($verifyPayment['reference'], $amount, $fee, WalletTransaction::TYPE['DEPOSIT'], 'Wallet Deposit');
    }
}
