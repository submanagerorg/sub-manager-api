<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode(File::get("database/seeders/data/services.json"));
        
        foreach($data as $datum){
            $category = Category::where('name', $datum->category)->first();

            if (!$category) {
                $category = new Category();
                $category->uid = Str::orderedUuid();
                $category->name = $datum->category;
                $category->save();
            }  

            $service = Service::where('name', $datum->name)->first();

            if (!$service) {
                $service = new Service();
                $service->uid = Str::orderedUuid();
            }   

            $service->name = $datum->name;
            $service->url = $datum->url;
            $service->category_id = $category->id;
            $service->save();
        }
       
    }
}
