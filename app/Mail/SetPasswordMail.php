<?php

namespace App\Mail;

use App\Models\PasswordReset;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Str;

class SetPasswordMail extends Mailable implements ShouldQueue
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
        return $this->subject('Set Password')
            ->view('emails.set-password')
            ->with([
                'amount' => Transaction::DEFAULT_CURRENCY['SIGN'] . $this->data['amount'],
                'plan' => ucfirst("{$this->data['pricing_plan']->name} Plan ({$this->data['pricing_plan']->period})"),
                'set_password_url' => $this->generateSetPasswordUrl()
            ]);
    }

    protected function generateSetPasswordUrl()
    {
        $token = Str::random(32);

        PasswordReset::updateOrCreate(
            ['email' => $this->data['email']],
            [
                'token' => $token,
                'created_at' => now(),
            ]
        );

        $url = config('app.web_app_url'). "/password/set?token=$token";

        return $url;
    }
}
