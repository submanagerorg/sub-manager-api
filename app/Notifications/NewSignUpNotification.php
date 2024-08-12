<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;

class NewSignUpNotification extends Notification
{
    // use Queueable;

    protected $details;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if(config('app.env') !== 'production'){
            return [];
        }

        return ['slack'];
    }


    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->success()
            ->content('🎉 *New User Sign Up!*')
            ->attachment(function ($attachment) {
                $attachment->title('User Details')
                           ->fields([
                               '👤 Email' => $this->details['email'],
                               '👥 Total Users' => User::count(),
                           ]);
            });
    }
}
