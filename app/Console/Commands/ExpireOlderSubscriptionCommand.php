<?php

namespace App\Console\Commands;

use App\Mail\SubscriptionExpiringMail;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ExpireOlderSubscriptionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire older subscription';

    protected $selectedSubscriptions = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $subscriptions = Subscription::where('status', Subscription::STATUS['ACTIVE'])
                    ->where('status', Subscription::STATUS['ACTIVE'])
                    ->whereDate('end_date', '<', now())
                    ->get();

        foreach($subscriptions as $subscription){
            $subscription->update([
                'status' =>  Subscription::STATUS['EXPIRED']
            ]);

            $this->groupSubscription($subscription);
        }

        foreach($this->selectedSubscriptions as $userId => $subscriptions){
            $user = User::where('id', $userId)->first();

            $mailData = [
                'username' => $user->username,
                'subscriptions' => $subscriptions,
                'days_left' => 0
            ];

            Mail::to($user->email)->send(new SubscriptionExpiringMail($mailData)); 
        }
       

        return 0;
    }

    
    /**
     * Group the selected subscriptions by user.
     *
     * @return array
     */
    public function groupSubscription($subscription) 
    {
        $userId = $subscription->user_id;

        if (!isset($this->selectedSubscriptions[$userId])) {
            $this->selectedSubscriptions[$userId] = [];
        }

        $this->selectedSubscriptions[$userId][] = $subscription;
    }

   
}
