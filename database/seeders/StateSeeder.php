<?php

namespace Database\Seeders;

use App\Models\Location\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class StateSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('data/states.json');

        $states = json_decode(File::get($jsonPath), true);

        collect($states)->chunk(1000)->each(function ($batch) {
            State::insert($batch->toArray());
        });
    }
}
