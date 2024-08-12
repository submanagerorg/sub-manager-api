<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;

class ErrorNotification extends Notification
{
    protected $errorMessage;
    protected $report;

    public function __construct($errorMessage, $report = [])
    {
        $this->errorMessage = $errorMessage;
        $this->report = $report;
    }

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
            ->error()
            ->content('⚠️ *Error Alert!*')
            ->attachment(function ($attachment) {
                $attachment->title('Error Details')
                    ->fields([
                        'Message' => $this->errorMessage,
                        'Timestamp' => now()->toDateTimeString(),
                        'Report' => json_encode($this->report, JSON_PRETTY_PRINT)
                    ]);
            });
    }
}
