<?php

namespace Database\Seeders;

use App\Models\Location\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('data/countries.json');

        $countries = json_decode(File::get($jsonPath), true);

        collect($countries)->each(function ($country) {
            Country::create($country);
        });
    }
}
