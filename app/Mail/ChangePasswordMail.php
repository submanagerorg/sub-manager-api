<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Messages\MailMessage;

class ChangePasswordMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $username = ucfirst($this->user->username) ?? 'Stranger';

        return $this->subject("Password Change")
            ->html((new MailMessage)
            ->greeting("Hello {$username},")
            ->line("You have successfully updated your password and it works like a charm!")
            ->line("If you did not do this update, quickly reach out to us via admin@submanager.com for urgent action.")
            ->line('--')
            ->line('Kind regards,')
            ->salutation('Subscription Manager')
            ->render()
            
        );

        //To do: Customized password change email 
        // return $this->subject('Password Change')
        // ->view('emails.change_password');
    }
}
