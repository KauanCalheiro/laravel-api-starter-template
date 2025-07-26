<?php

namespace Database\Seeders;

use App\Enums\ConnectionEnum;
use App\Models\Evidencia;
use DB;
use Exception;
use Illuminate\Database\Seeder;

class EvidenciaSeeder extends Seeder
{
    public function run(): void
    {
        $pivotTableIntegrante = 'ext_integrante_evidencia';
        $modelEvidencia       = new Evidencia();

        try {
            $evidenciasData = DB::connection(ConnectionEnum::ACADEMICO)
                ->table('acd_atividades_extensionistas')
                ->get();

            $dadosIntegrantes = DB::connection(ConnectionEnum::ACADEMICO)
                ->table('acd_integrantes_atividades_extensionistas')
                ->select('ref_atividade_extensionista', 'ref_pessoa')
                ->get()
                ->groupBy('ref_atividade_extensionista');
        } catch (Exception $e) {
            $this->command->warn("Não foi possível conectar com o banco acadêmico: {$e->getMessage()}");
            return;
        }

        if ($evidenciasData->isEmpty()) {
            $this->command->warn('Nenhum dado encontrado na tabela acd_atividades_extensionistas');
            return;
        }

        DB::transaction(function () use ($evidenciasData, $dadosIntegrantes, $pivotTableIntegrante, $modelEvidencia) {
            foreach ($evidenciasData as $row) {
                DB::table($modelEvidencia->getTable())->updateOrInsert(
                    ['id' => $row->id],
                    [
                        'ref_acao'                      => $row->ref_acao_extensionista,
                        'titulo'                        => strip_tags(mb_convert_encoding($row->titulo, 'UTF-8', 'ISO-8859-1')),
                        'descricao'                     => strip_tags(mb_convert_encoding($row->atividade_realizada, 'UTF-8', 'ISO-8859-1')),
                        'qtde_participantes_comunidade' => $row->qtde_participantes_comunidade,
                        'feedback'                      => strip_tags(mb_convert_encoding($row->feedback, 'UTF-8', 'ISO-8859-1')),
                        'dt_evidencia'                  => $row->dt_atividade,
                        'dt_avaliada_professor'         => $row->dt_avaliada_professor,
                        'created_at'                    => $row->created_at,
                        'updated_at'                    => $row->last_updated_at,
                    ],
                );

                foreach ($dadosIntegrantes->get($row->id, []) as $integrante) {
                    DB::table($pivotTableIntegrante)->updateOrInsert(
                        [
                            'ref_evidencia' => $row->id,
                            'ref_pessoa'    => $integrante->ref_pessoa,
                        ],
                    );
                }
            }
        });

        $this->atualizarSequence($modelEvidencia->getTable());
        $this->atualizarSequence($pivotTableIntegrante);
    }

    private function atualizarSequence(string $table): void
    {
        $maxId = DB::table($table)->max('id');
        if ($maxId) {
            $sequence = "{$table}_id_seq";
            DB::statement("SELECT setval('{$sequence}', {$maxId})");
        }
    }
}
