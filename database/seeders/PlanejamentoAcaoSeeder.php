<?php

namespace Database\Seeders;

use App\Enums\ConnectionEnum;
use App\Models\PlanejamentoAcao;
use App\Models\TurmaExtensao;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanejamentoAcaoSeeder extends Seeder
{
    public function run(): void
    {
        $pivotTable = 'ext_turma_planejamento_acao';

        try {
            $planejamentoData = DB::connection(ConnectionEnum::ACADEMICO)
                ->table('acd_sinteses_turmas')
                ->get();
        } catch (\Exception $e) {
            $this->command->warn("Não foi possível conectar com o banco acadêmico: {$e->getMessage()}");
            return;
        }

        if ($planejamentoData->isEmpty()) {
            $this->command->warn('Nenhum dado encontrado na tabela acd_sinteses_turmas');
            return;
        }

        DB::transaction(function () use ($planejamentoData, $pivotTable) {
            foreach ($planejamentoData as $row) {
                $turma = TurmaExtensao::findOrFail($row->ref_turma);

                PlanejamentoAcao::updateOrCreate(
                    ['id' => $row->id],
                    [
                        'titulo'       => $turma->disciplina,
                        'planejamento' => strip_tags(mb_convert_encoding($row->sintese, 'UTF-8', 'ISO-8859-1')),
                        'created_at'   => $row->created_at,
                        'updated_at'   => $row->last_updated_at,
                    ],
                );

                DB::table($pivotTable)->updateOrInsert(
                    [
                        'ref_turma'        => $row->ref_turma,
                        'ref_planejamento' => $row->id,
                    ],
                    [
                        'created_at' => $row->created_at,
                        'updated_at' => $row->last_updated_at,
                    ],
                );
            }
        });

        $maxId = PlanejamentoAcao::max('id');
        if ($maxId) {
            $table    = (new PlanejamentoAcao())->getTable();
            $sequence = "{$table}_id_seq";
            \DB::statement("SELECT setval('{$sequence}', {$maxId})");
        }
    }
}
