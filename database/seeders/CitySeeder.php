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

        collect($cities)->chunk(1000)->each(function ($batch) {
            City::insert($batch->toArray());
        });
    }
}
