<?php

use App\Models\Cidade;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    protected Cidade $model;

    public function __construct()
    {
        $this->model = new Cidade();
    }

    public function up(): void
    {
        Schema::create($this->model->getTable(), function (Blueprint $table) {
            $table->id($this->model->getKeyName());
            $table->text('nome');
            $table->integer('ref_estado');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->model->getTable());
    }
};
