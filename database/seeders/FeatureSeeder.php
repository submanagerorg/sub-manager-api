<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode(File::get("database/seeders/data/features.json"));
        
        foreach($data as $datum){
            $feature = Feature::where('name', $datum->name)->first();

            if ($feature) {
                continue;
            }   

            $feature = new Feature();
            $feature->uid = Str::orderedUuid();
            $feature->name = $datum->name;
            $feature->save();
        }
       
    }
}
