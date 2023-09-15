<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Euro',
                'sign' => '€',
                'symbol' => 'EUR'
            ],
            [
                'name' => 'United States dollar',
                'sign' => '$',
                'symbol' => 'USD'
            ],
            [
                'name' => 'Nigerian naira',
                'sign' => '₦',
                'symbol' => 'NGN'
            ],
            [
                'name' => 'British pound',
                'sign' => '£',
                'symbol' => 'GBP'
            ],
        ];


        foreach($data as $datum){
            $currency = Currency::where('name', $datum['name'])->first();

            if (!$currency) {
                $currency = new Currency();
                $currency->uid = Str::orderedUuid();
            }   

            $currency->name = $datum['name'];
            $currency->sign = $datum['sign'];
            $currency->symbol = $datum['symbol'];
            $currency->save();
        }
       
    }
}
