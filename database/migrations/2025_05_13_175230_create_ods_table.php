<?php

use App\Models\Ods;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    protected Ods $model;

    public function __construct()
    {
        $this->model = new Ods();
    }

    public function up(): void
    {
        Schema::create($this->model->getTable(), function (Blueprint $table) {
            $table->id($this->model->getKeyName());
            $table->string('nome')->unique();
            $table->string('descricao')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->model->getTable());
    }
};
