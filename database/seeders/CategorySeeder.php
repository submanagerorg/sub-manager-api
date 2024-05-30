<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode(File::get("database/seeders/data/categories.json"));
        
        foreach($data as $datum){
            $category = Category::where('name', $datum->name)->first();

            if (!$category) {
                $category = new Category();
                $category->uid = Str::orderedUuid();
            }    

            $category->name = $datum->name;
            $category->colour = $datum->colour;
            $category->save();
        }
       
    }
}
