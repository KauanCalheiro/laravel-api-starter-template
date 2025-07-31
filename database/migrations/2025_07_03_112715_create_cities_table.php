<?php

use App\Models\Location\City;
use App\Models\Location\State;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    protected City $city;
    protected State $state;

    public function __construct()
    {
        $this->city  = new City();
        $this->state = new State();
    }

    public function up(): void
    {
        Schema::create($this->city->getTable(), function (Blueprint $table) {
            $table->id($this->city->getKeyName());
            $table->foreignId('state_id')->constrained($this->state->getTable());
            $table->text('name');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->city->getTable());
    }
};
