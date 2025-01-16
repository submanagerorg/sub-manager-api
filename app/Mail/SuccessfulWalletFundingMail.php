<?php

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Messages\MailMessage;

class SuccessfulWalletFundingMail extends Mailable implements ShouldQueue
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
        return $this->subject('Wallet Funding Successful')
            ->view('emails.wallet-funding-success')
            ->with([
                'reference' =>  $this->data['reference'],
                'amount' => Transaction::DEFAULT_CURRENCY['SIGN'] . number_format($this->data['amount'], 2, '.', ','),
                'description' => $this->data['description'],
                'dateTime' => $this->data['dateTime'],
                'balance' => Transaction::DEFAULT_CURRENCY['SIGN'] . number_format($this->data['balance'], 2, '.', ','),
            ]);
    }
}
