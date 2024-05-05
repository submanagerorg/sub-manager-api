<?php

namespace App\Repositories;

use App\Models\Currency;
use App\Models\Subscription;

class DashboardRepository {
    public function totalSummaryData(string $currency) {
        $currency = Currency::whereSymbol($currency)->first();
        $subscriptions = Subscription::whereUserId(auth()->id())->whereCurrencyId($currency->id)->get();

        return [
            'total_entries' => $subscriptions->count(),
            'month_average' => round($subscriptions->count() / 30, 1),
            'total_amount_spent' => $subscriptions->sum('amount')
        ];
    }
}
