<?php

namespace Database\Seeders;

use App\Models\Location\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('data/cities.json');

        $cities = json_decode(File::get($jsonPath), true);

        collect($cities)->each(function ($city) {
            City::create($city);
        });
    }
}
