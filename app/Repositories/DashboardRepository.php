<?php

namespace App\Repositories;

use App\Models\Currency;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardRepository {
    public function totalSummaryData(string $currency): array {
        $currency = Currency::whereCode($currency)->first();
        $subscriptions = Subscription::whereUserId(auth()->id())->whereCurrencyId($currency->id)->get();

        return [
            'total_entries' => $subscriptions->count(),
            'month_average' => round($subscriptions->count() / 30, 1),
            'total_amount_spent' => $subscriptions->sum('amount')
        ];
    }

    public function spendByCurrencyData() {
        $userId = auth()->id();
        $subcriptions = Subscription::toBase()
                        ->whereUserId($userId)
                        ->join(
                            'currencies', function($join) {
                                $join->on('subscriptions.currency_id', '=', 'currencies.id');
                            }
                        )->select(
                            'symbol',
                            DB::raw('SUM(amount) as total_amount'),
                            DB::raw("ROUND((SUM(amount) / (SELECT SUM(amount) FROM subscriptions where user_id = $userId)) * 100, 0) as percentage")
                        )
                        ->groupBy('subscriptions.currency_id')
                        ->get();

        return $subcriptions;
    }

    public function spendByCategoryData() {
        $userId = auth()->id();
        $subcriptions = Subscription::toBase()
                        ->whereUserId($userId)
                        ->join(
                            'categories', function($join) {
                                $join->on('subscriptions.category_id', '=', 'categories.id');
                            }
                        )->select(
                            'categories.name',
                            DB::raw('SUM(amount) as total_amount'),
                            DB::raw("ROUND((SUM(amount) / (SELECT SUM(amount) FROM subscriptions where user_id = $userId)) * 100, 0) as percentage")
                        )
                        ->groupBy('subscriptions.category_id')
                        ->get();
        return $subcriptions;
    }

    public function expirySoonData() {
        $today = Carbon::today();
        $maxExpirationDay = Carbon::today()->addDays(config('subscription.number_of_days_to_warn_expiration'));
        $subscriptions = Subscription::with(['category:id,name'])
                            ->whereUserId(auth()->id())
                            ->whereBetween('end_date', [$today, $maxExpirationDay])
                            ->get();

        return $subscriptions;
    }

    public function getGraphData(string|null $period = null) {
        $currentYear = now()->year;

        if ($period === 'month') {
            return Subscription::toBase()->select(
                DB::raw('YEAR(created_at) as subscription_year'),
                DB::raw('MONTH(created_at) as subscription_month'),
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('MONTHNAME(created_at) as month_name')
            )
            ->whereYear('created_at', $currentYear)
            ->where('user_id', auth()->id())
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'), DB::raw('MONTHNAME(created_at)'))
            ->orderBy('subscription_year')
            ->orderBy('subscription_month')
            ->get();
        }

        return Subscription::toBase()->select(
            DB::raw('YEAR(created_at) as subscription_year'),
            DB::raw('SUM(amount) as total_amount')
        )
        ->where('user_id', auth()->id())
        ->groupBy(DB::raw('YEAR(created_at)'))
        ->orderBy('subscription_year')
        ->get();
    }
}
