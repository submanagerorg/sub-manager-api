<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subscription;
use Illuminate\Database\Seeder;

class SeedRandomCategoriesForExistingSubs extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = Category::inRandomOrder()->latest()->first();
        $subscriptions = Subscription::get();

        foreach($subscriptions as $subscription) {
            $category = Category::inRandomOrder()->latest()->first();
            $subscription->update(['category_id' => $category->id]);
        }
    }
}
