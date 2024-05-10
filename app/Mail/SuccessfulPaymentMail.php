<?php

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Messages\MailMessage;

class SuccessfulPaymentMail extends Mailable implements ShouldQueue
{
    // use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Payment Successful')
            ->view('emails.successful-payment')
            ->with([
                'plan' => ucfirst("{$this->data['pricing_plan']->name} Plan ({$this->data['pricing_plan']->period})"),
                'reference' =>  $this->data['reference'],
                'amount' => Transaction::DEFAULT_CURRENCY['SIGN'] . $this->data['amount'],
                'user_pricing_plan' => $this->data['user']->userPricingPlan
            ]);
    }
}
