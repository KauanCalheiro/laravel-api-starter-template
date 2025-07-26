<?php

use App\Models\Pais;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    protected Pais $model;

    public function __construct()
    {
        $this->model = new Pais();
    }

    public function up(): void
    {
        Schema::create($this->model->getTable(), function (Blueprint $table) {
            $table->id($this->model->getKeyName());
            $table->text('nome');
            $table->string('sigla', 3);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->model->getTable());
    }
};
