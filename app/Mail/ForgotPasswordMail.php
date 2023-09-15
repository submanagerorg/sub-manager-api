<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Messages\MailMessage;

class ForgotPasswordMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;

    public $reset_url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $reset_url)
    {
        $this->user = $user;
        $this->reset_url = $reset_url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $username = ucfirst($this->user->username) ?? 'Stranger';

        return $this->subject("Reset Your Password")
            ->html((new MailMessage)
            ->greeting("Hello {$username},")
            ->line("We just got your request for a password reset")
            ->line("If you did not initiate this request, ignore the link and reach out to us via admin@submanager.com for further investigations.")
            ->line("In order to reset your password, please click on the link below. Link expires in 30 minutes.")
            ->action('Password Reset', $this->reset_url)
            ->line('--')
            ->line('Kind regards,')
            ->salutation('Subscription Manager')
            ->render()
            
        );

        //To do: Customized reset password email
        // return $this->subject('Reset Your Password')
        // ->view('emails.reset_password');
    }
}
