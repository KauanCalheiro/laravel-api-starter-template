<?php

use App\Enums\DriverEnum;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    public function up()
    {
        if (DB::getDriverName() !== DriverEnum::POSTGRES) {
            return;
        }

        DB::statement('CREATE EXTENSION IF NOT EXISTS unaccent');
    }

    public function down()
    {
        if (DB::getDriverName() !== DriverEnum::POSTGRES) {
            return;
        }

        DB::statement('DROP EXTENSION IF EXISTS unaccent');
    }
};
