<?php

use App\Models\Location\Country;
use App\Models\Location\State;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    protected State $state;
    protected Country $country;

    public function __construct()
    {
        $this->state   = new State();
        $this->country = new Country();
    }

    public function up(): void
    {
        Schema::create($this->state->getTable(), function (Blueprint $table) {
            $table->id($this->state->getKeyName());
            $table->foreignId('country_id')->constrained($this->country->getTable());
            $table->text('name');
            $table->string('code', 3);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->state->getTable());
    }
};
