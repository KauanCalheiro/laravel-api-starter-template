<?php

use App\Models\TurmaExtensao;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    protected TurmaExtensao $model;

    public function __construct()
    {
        $this->model = new TurmaExtensao();
    }
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('
            CREATE FOREIGN TABLE IF NOT EXISTS foreign_table_acd_turmas_extensao (
                ref_turma integer,
                ementa text,
                dt_encerramento_turma timestamp without time zone,
                ref_disciplina integer,
                disciplina text,
                carga_horaria_extensao integer,
                carga_horaria double precision,
                ref_curriculo integer,
                curriculo text,
                ref_periodo character varying(10),
                dt_inicial_periodo date,
                dt_final_periodo date,
                ref_coordenador integer,
                coordenador character varying(100),
                fl_ativa boolean,
                professores jsonb,
                dias_semana jsonb
            ) SERVER alfa OPTIONS (table_name \'materialized_view_turmas_extensao\')
        ');
        DB::statement("
            CREATE MATERIALIZED VIEW {$this->model->getTable()} AS (
            SELECT
                ref_turma,
                ementa,
                dt_encerramento_turma,
                ref_disciplina,
                disciplina,
                carga_horaria_extensao,
                carga_horaria,
                ref_curriculo,
                curriculo,
                ref_periodo,
                dt_inicial_periodo,
                dt_final_periodo,
                ref_coordenador,
                coordenador,
                fl_ativa,
                professores,
                dias_semana
            FROM
                foreign_table_acd_turmas_extensao
            )
        ");

        DB::statement("
            CREATE INDEX {$this->model->getTable()}_ref_turma_idx ON {$this->model->getTable()}(ref_turma)
        ");
    }
};
