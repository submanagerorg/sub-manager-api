<?php

namespace App\Console\Commands;

use App\Mail\SubscriptionExpiringMail;
use App\Models\PricingPlan;
use App\Models\Service;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SubscriptionExpiryReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:expiry-reminder {days_left}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscription reminders when there is a specified number of day(s) left before expiration';

    protected $selectedSubscriptions = [];

    protected $daysLeft;

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
        DB::beginTransaction();

        try {
            $this->daysLeft = $this->argument('days_left');
        
            $daysFromNow = Carbon::now()->addDays($this->daysLeft);

            $subscriptions = Subscription::where('status', Subscription::STATUS['ACTIVE'])
                        ->where('status', Subscription::STATUS['ACTIVE'])
                        ->whereDate('end_date', $daysFromNow)
                        ->get();

            foreach($subscriptions as $subscription){
                $timezone = $subscription->user->timezone;
                $timezoneName = $timezone->zone_name;

                $todayInTimezone = Carbon::now()->setTimezone($timezoneName);
                $todayStartInTimezone = Carbon::now()->setTimezone($timezoneName)->startOfDay();
                $daysFromNowInTimezoneDate = Carbon::now()->setTimezone($timezoneName)->addDays($this->daysLeft)->format('Y-m-d');
                $endDate = Carbon::parse($subscription->end_date)->shiftTimezone($timezoneName)->format('Y-m-d');

                if ($endDate == $daysFromNowInTimezoneDate) {
                    
                    if ($todayInTimezone->diffInHours($todayStartInTimezone) <= 5){
                        if($this->daysLeft == 0) {
                            $this->expireSubscription($subscription);
                        }

                        $this->groupSubscription($subscription);
                    }
                }

            }

            foreach($this->selectedSubscriptions as $userId => $subscriptions){
                $user = User::where('id', $userId)->first();

                $mailData = [
                    'username' => $user->username,
                    'subscriptions' => $subscriptions,
                    'days_left' => $this->daysLeft
                ];

                Mail::to($user->email)->send(new SubscriptionExpiringMail($mailData)); 

                DB::commit();
            }
        } catch(Throwable $e) {
            DB::rollBack();

            report($e);
        }
       
        return 0;
    }

    
    /**
     * Group the selected subscriptions by user.
     *
     * @return void
     */
    public function groupSubscription($subscription) 
    {
        $userId = $subscription->user_id;

        if (!isset($this->selectedSubscriptions[$userId])) {
            $this->selectedSubscriptions[$userId] = [];
        }

        $this->selectedSubscriptions[$userId][] = $subscription;
    }

     /**
     * Expire subscription.
     *
     * @return void
     */
    public function expireSubscription($subscription) 
    {
        $subscription->update([
            'status' =>  Subscription::STATUS['EXPIRED']
        ]);

        if ($subscription->name == Service::DEFAULT_SERVICE) {
            $pricingPlan = PricingPlan::where('name', PricingPlan::DEFAULT_PLAN)->first();
            $subscription->user->addUserPricingPlan($pricingPlan);
        }
    }

   
}
