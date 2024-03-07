<?php


namespace App\Http\Filters;

use App\Http\Filters\Filter;
use App\Models\Currency;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class SubscriptionFilter extends Filter
{
    /**
     * Filter the subscriptions by latest.
     *
     * @param string|null $value
     * @return Builder
     */
    public function default(string $value = null): Builder
    {
        return $this->builder->oldest('end_date');
    }

    /**
     * Filter the subscriptions by the given name.
     *
     * @param string|null $value
     * @return Builder
     */
    public function name(string $value = null): Builder
    {
        if (isset($value)) {
            return $this->builder->where('name', 'like', "%{$value}%");
        }

        return $this->builder;
    }

    /**
     * Filter the subscriptions by the given status.
     *
     * @param string|null $value
     * @return Builder
     */
    public function status(string $value = null): Builder
    {
        if (isset($value)) {
            return $this->builder->where('status', $value);
        }

        return $this->builder;
    }

    /**
     * Filter the subscriptions by the given currency.
     *
     * @param string|null $value
     * @return Builder
     */
    public function currency(string $value = null): Builder
    {
        if (isset($value)) {
            $currency = Currency::where('code', $value)->first();

            return $this->builder->where('currency_id', $currency ? $currency->id : null);
        }

        return $this->builder;
    }

    /**
     * Filter the subscriptions by the given amount.
     *
     * @param string|null $value
     * @return Builder
     */
    public function amount(array $value = []): Builder
    {
        if (isset($value['from']) && isset($value['to'])) {
            return $this->builder->where('amount','>=',$value['from'])->where('amount', '<=', $value['to'])->oldest('amount');
        }

        return $this->builder->where('amount', $value['from'] ?? $value['to']);
    }


    /**
     * Filter the subscriptions by the given start date.
     *
     * @param array $value
     * @return Builder
     */
    public function startDate(array $value = []): Builder
    {
        if (isset($value['from']) && isset($value['to'])) {
            return $this->builder->whereDate('start_date','>=',Carbon::parse($value['from']))->whereDate('start_date', '<=', Carbon::parse($value['to']))->oldest('start_date');
        }

        return $this->builder->whereDate('start_date', $value['from'] ?? $value['to']);
    }

    /**
     * Filter the subscriptions by the given end date.
     *
     * @param array $value
     * @return Builder
     */
    public function endDate(array $value = []): Builder
    {
        if (isset($value['from']) && isset($value['to'])) {
            return $this->builder->whereDate('end_date','>=',Carbon::parse($value['from']))->whereDate('end_date', '<=', Carbon::parse($value['to']))->oldest('end_date');
        }

        return $this->builder->whereDate('end_date', $value['from'] ?? $value['to']);
    }

    /**
     * Sort the subscriptions by day.
     *
     * @param string|null $value
     * @return Builder
     */
    public function period(string $value = null): Builder
    {
        if (isset($value) &&  $value === 'today') {
            return $this->builder->whereDate('created_at', Carbon::today());
        }

        if (isset($value) &&  $value === 'yesterday') {
            return $this->builder->whereDate('created_at', Carbon::yesterday());
        }

        if (isset($value) &&  $value === 'last-7-days') {
            return $this->builder->whereDate('created_at', '>=', Carbon::today()->subDays(7));
        }

        if (isset($value) &&  $value === 'last-30-days') {
            return $this->builder->whereDate('created_at', '>=', Carbon::today()->subDays(30));
        }

        return $this->builder->whereDate('created_at', Carbon::today());
    }
}
