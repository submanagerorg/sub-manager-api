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
                            'currencies.code',
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
                            'categories.colour',
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

    public function getGraphData(string|null $period = null, string|null $currency) {
        $currentYear = now()->year;
        $data = [];
        $months = ['January', 'February', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $currencyId = optional(Currency::whereCode($currency)->first())->id;

        if ($period === 'month') {
            $query = Subscription::toBase()->select(
                DB::raw('YEAR(start_date) as subscription_year'),
                DB::raw('MONTH(start_date) as subscription_month'),
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('MONTHNAME(start_date) as month_name')
            )
            ->whereYear('created_at', $currentYear)
            ->where('user_id', auth()->id())
            ->groupBy(DB::raw('YEAR(start_date)'), DB::raw('MONTH(start_date)'), DB::raw('MONTHNAME(start_date)'))
            ->orderBy('subscription_year')
            ->orderBy('subscription_month');

            if ($currencyId) {
                $query->where('currency_id', $currencyId);
            }

            $data = $query->get()->toArray();

            $dataMonths = array_column($data, 'month_name');
            $newData = [];

            foreach ($months as $month) {
                if (!in_array($month, $dataMonths)) {
                    $newData[] = [
                        'subscription_year' => today()->year(),
                        'subscription_month' => 0,
                        'total_amount' => 0,
                        'month_name' => $month
                    ];
                } else {
                    $item = collect($data)->firstWhere('month_name', $month);
                    $newData[] = [
                        'subscription_year' => $item->subscription_year,
                        'subscription_month' => $item->subscription_month,
                        'total_amount' => $item->total_amount,
                        'month_name' => $month
                    ];
                }
            }

            $data = $newData;
        } else {
            $query = Subscription::toBase()->select(
                DB::raw('YEAR(created_at) as subscription_year'),
                DB::raw('SUM(amount) as total_amount')
            )
            ->where('user_id', auth()->id())
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy('subscription_year');

            if ($currencyId) {
                $query->where('currency_id', $currencyId);
            }

            $data = $query->get()->toArray();
        }

        return $data;
    }

    public function getMostRenewed(string|null $type = 'most') {
        if ($type === 'least') {
            return Subscription::toBase()
                        ->select('parent_id', 'name', DB::raw('COUNT(*) as num_renewed'))
                        ->whereNotNull('parent_id')
                        ->groupBy('parent_id', 'name')
                        ->orderBy('num_renewed', 'asc')
                        ->limit(5)
                        ->get();
        }

        return Subscription::toBase()
                        ->select('parent_id', 'name', DB::raw('COUNT(*) as num_renewed'))
                        ->whereNotNull('parent_id')
                        ->groupBy('parent_id', 'name')
                        ->orderBy('num_renewed', 'desc')
                        ->limit(5)
                        ->get();
    }
}
