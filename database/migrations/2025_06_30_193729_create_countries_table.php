<?php

use App\Models\Location\Country;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    protected Country $country;

    public function __construct()
    {
        $this->country = new Country();
    }

    public function up(): void
    {
        Schema::create($this->country->getTable(), function (Blueprint $table) {
            $table->id($this->country->getKeyName());
            $table->text('name');
            $table->string('code', 3);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->country->getTable());
    }
};
