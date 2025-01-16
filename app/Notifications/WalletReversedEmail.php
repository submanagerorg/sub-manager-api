<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WalletReversedEmail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(private array $data)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Wallet Reversal Successful')
                    ->view('emails.wallet-reversed', [
                        'reference' =>  $this->data['reference'],
                        'amount' => Transaction::DEFAULT_CURRENCY['SIGN'] . number_format($this->data['amount'], 2, '.', ','),
                        'description' => $this->data['description'],
                        'dateTime' => $this->data['dateTime'],
                        'balance' => Transaction::DEFAULT_CURRENCY['SIGN'] . number_format($this->data['balance'], 2, '.', ','),
                    ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
