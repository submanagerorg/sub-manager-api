<?php

namespace App\Repositories;

use App\Models\Currency;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;

class DashboardRepository {
    public function totalSummaryData(string $currency): array {
        $currency = Currency::whereSymbol($currency)->first();
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
}
