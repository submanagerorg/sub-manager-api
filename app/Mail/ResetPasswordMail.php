<?php

namespace App\Mail;

use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Str;

class ResetPasswordMail extends Mailable implements ShouldQueue
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
        $isMobile = preg_match('/\b(Mobile|Android|iPhone|iPad|Windows Phone)\b/i', $this->data['user_agent']);

        if ($isMobile) {
            return $this->subject('Password Reset')
            ->view('emails.reset-password-code')
            ->with([
                'reset_code' => str_split($this->generateResetCode())
            ]);
        }

        return $this->subject('Password Reset')
            ->view('emails.reset-password')
            ->with([
                'reset_url' => $this->generateResetUrl()
            ]);
    }

    protected function generateResetUrl()
    {
        $token = Str::random(32);

        PasswordReset::updateOrCreate(
            ['email' => $this->data['user']->email],
            [
                'token' => $token,
                'created_at' => now(),
            ]
        );

        $resetUrl = config("app.web_app_url") . "/password/reset?token=$token";

        return $resetUrl;
    }

    protected function generateResetCode()
    {
        $token = random_int(100000, 999999);

        PasswordReset::updateOrCreate(
            ['email' => $this->data['user']->email],
            [
                'token' => $token,
                'created_at' => now(),
            ]
        );

        return $token;
    }
}
