<?php

namespace App\Parsers;

use Carbon\Carbon;

class DateParser
{
    public function getEndDate(Carbon $startDate, string $variationName): Carbon
    {
        [$period, $duration] = $this->parseVariationName($variationName);
        return $this->calculateEndDate($startDate, $period, $duration);
    }

    private function parseVariationName(string $variationName): array
    {
        $name = strtolower($variationName);
        
        return match (true) {
            str_contains($name, "1 day") => ['daily', 1],
            str_contains($name, "1 week") => ['weekly', 1],
            str_contains($name, "1 month") => ['monthly', 1],
            str_contains($name, "3 month") => ['monthly', 3],
            str_contains($name, "6 month") => ['monthly', 6],
            str_contains($name, "monthly") => ['monthly', 1],
            str_contains($name, "yearly") => ['yearly', 1],
            default => ['monthly', 1],
        };
    }

    private function calculateEndDate(Carbon $startDate, string $period, int $duration): Carbon
    {
        $startDate = clone $startDate;

        return match ($period) {
            'daily' => $startDate->addDays($duration),
            'weekly' => $startDate->addWeeks($duration),
            'monthly' => $startDate->addMonths($duration),
            'yearly' => $startDate->addYears($duration),
            default => $startDate->addMonths(1),
        };
    }
}
