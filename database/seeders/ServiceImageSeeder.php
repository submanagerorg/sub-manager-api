<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Service::get() as $service) {
            $service->update([
                'image_url' => $this->getImageUrl()[$service->name] ?? ''
            ]);
        }
    }

    private function getImageUrl()
    {
        return [
            'Dstv' => 'https://res.cloudinary.com/dwn7phnpa/image/upload/v1742655390/subsyncassets/DSTV_dqhkj5.png',
            'Startimes' => 'https://res.cloudinary.com/dwn7phnpa/image/upload/v1742655390/subsyncassets/startimes-logo-png_seeklogo-527209_n3tdwz.png',
            'Gotv' => 'https://res.cloudinary.com/dwn7phnpa/image/upload/v1742655390/subsyncassets/gotv-satellite-africa-logo-png_seeklogo-556435_x0cloj.png',
            'Showmax' => 'https://res.cloudinary.com/dwn7phnpa/image/upload/v1742655390/subsyncassets/Showmax_Logo_divzsi.png',
            'SubSync' => 'https://res.cloudinary.com/dwn7phnpa/image/upload/v1715429003/subsyncassets/email_assets/gy0mypzxprenm9qily0b.png',
        ];
    }
}
