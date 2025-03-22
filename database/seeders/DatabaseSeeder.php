<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(CurrencySeeder::class);
        $this->call(TimezoneSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(PricingPlanSeeder::class);
        $this->call(FeatureSeeder::class);
        $this->call(PricingPlanFeatureSeeder::class);
        $this->call(ServiceImageSeeder::class);
    }
}
