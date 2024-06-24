<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Messages\MailMessage;
use NumberFormatter;

class SubscriptionExpiringMail extends Mailable implements ShouldQueue
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
        $numberFormatter = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        $subCount = count($this->data['subscriptions']);
        $subCountInWords = $numberFormatter->format($subCount);

        if($this->data['days_left'] == 0) {
            return $this->subject('Subscription(s) Expiring Today')
            ->view('emails.expiring-today')
            ->with([
                'username' => $this->data['username'],
                'subscriptions' => $this->data['subscriptions'],
                'days_left' => $this->data['days_left'],
                'subscription_count' => "{$subCountInWords} ({$subCount})"
            ]);
        }

        return $this->subject('Subscription(s) Expiring Soon')
            ->view('emails.multiple-expiring-soon')
            ->with([
                'username' => $this->data['username'],
                'subscriptions' => $this->data['subscriptions'],
                'days_left' => $this->data['days_left'],
                'subscription_count' => "{$subCountInWords} ({$subCount})"
            ]);
        

    }
}
