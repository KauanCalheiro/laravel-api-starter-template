<?php

use App\Enums\DriverEnum;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    public function up()
    {
        if (!DriverEnum::match(DriverEnum::POSTGRES, DB::getDriverName())) {
            return;
        }

        DB::statement('CREATE EXTENSION IF NOT EXISTS unaccent');
    }

    public function down()
    {
        if (!DriverEnum::match(DriverEnum::POSTGRES, DB::getDriverName())) {
            return;
        }

        DB::statement('DROP EXTENSION IF EXISTS unaccent');
    }
};
