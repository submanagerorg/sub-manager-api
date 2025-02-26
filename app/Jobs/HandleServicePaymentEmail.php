<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\FailedServicePayment;
use App\Notifications\SuccessfulServicePaymentEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class HandleServicePaymentEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public bool $transactionSuccessful = false, public User $user = new User(), public string $serviceName = '')
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info("Job: Sending email for service payment");
        if ($this->transactionSuccessful) {
            $this->sendSuccessEmail($this->user, $this->serviceName);
        } else {
            $this->sendFailueEmail($this->user, $this->serviceName);
        }

        Log::info("Job: Email sent successfully");
    }

    private function sendSuccessEmail(User $user, string $serviceName) {
        $user->notify(new SuccessfulServicePaymentEmail($serviceName));
    }

    private function sendFailueEmail(User $user, string $serviceName) {
        $user->notify(new FailedServicePayment($serviceName));
    }
}
