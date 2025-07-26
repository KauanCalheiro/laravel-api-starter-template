<?php

namespace Database\Seeders;

use App\Enums\ConnectionEnum;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcaoSeeder extends Seeder
{
    public function run(): void
    {
        $pivotTableIntegrantes = 'ext_integrante_acao';
        $pivotTableODS         = 'ext_ods_acao';

        try {
            $acoesData = DB::connection(ConnectionEnum::ACADEMICO)
                ->table('acd_acoes_extensionistas')
                ->get();

            $dadosIntegrantes = DB::connection(ConnectionEnum::ACADEMICO)
                ->table('acd_integrantes_grupos_extensionistas')
                ->join('acd_grupos_extensionistas', 'acd_integrantes_grupos_extensionistas.ref_grupo', '=', 'acd_grupos_extensionistas.id')
                ->select(
                    'acd_grupos_extensionistas.ref_acao_extensionista as ref_acao',
                    'acd_integrantes_grupos_extensionistas.ref_pessoa',
                )
                ->get()
                ->groupBy('ref_acao');

            $dadosOds = DB::connection(ConnectionEnum::ACADEMICO)
                ->table('acd_ods_acoes_extensionistas')
                ->get()
                ->groupBy('ref_acao_extensionista');
        } catch (Exception $e) {
            $this->command->warn("Erro ao acessar dados do banco acadêmico: {$e->getMessage()}");
            return;
        }

        if ($acoesData->isEmpty()) {
            $this->command->warn('Nenhum dado encontrado na tabela acd_acoes_extensionistas');
            return;
        }

        DB::transaction(function () use ($acoesData, $dadosIntegrantes, $dadosOds, $pivotTableIntegrantes, $pivotTableODS) {
            foreach ($acoesData as $acao) {
                DB::table('ext_acao')->updateOrInsert(
                    ['id' => $acao->id],
                    [
                        'ref_planejamento'                          => $acao->ref_sintese_turma,
                        'ref_programa_extensao'                     => $acao->ref_programa_extensao,
                        'ref_projeto_extensao'                      => $acao->ref_projeto_extensao,
                        'ref_cidade'                                => $acao->ref_cidade,
                        'titulo'                                    => strip_tags(mb_convert_encoding($acao->titulo, 'UTF-8', 'ISO-8859-1')),
                        'descricao'                                 => strip_tags(mb_convert_encoding($acao->atividade, 'UTF-8', 'ISO-8859-1')),
                        'territorio_educativo'                      => strip_tags(mb_convert_encoding($acao->territorio_educativo, 'UTF-8', 'ISO-8859-1')),
                        'nome_responsavel_territorio_educativo'     => strip_tags(mb_convert_encoding($acao->nome_responsavel_territorio_educativo, 'UTF-8', 'ISO-8859-1')),
                        'email_responsavel_territorio_educativo'    => strip_tags(mb_convert_encoding($acao->email_responsavel_territorio_educativo, 'UTF-8', 'ISO-8859-1')),
                        'telefone_responsavel_territorio_educativo' => $acao->telefone_responsavel_territorio_educativo,
                        'dt_encerramento'                           => $acao->dt_encerramento,
                        'dt_aprovada_professor'                     => $acao->dt_aprovada_professor,
                        'dt_recusada_professor'                     => $acao->dt_recusada_professor,
                        'feedback_recusado'                         => strip_tags(mb_convert_encoding($acao->feedback_recusado, 'UTF-8', 'ISO-8859-1')),
                        'feedback_encerramento'                     => strip_tags(mb_convert_encoding($acao->feedback_encerramento, 'UTF-8', 'ISO-8859-1')),
                        'created_at'                                => $acao->created_at,
                        'updated_at'                                => $acao->last_updated_at,
                    ],
                );

                foreach ($dadosIntegrantes->get($acao->id, []) as $integrante) {
                    DB::table($pivotTableIntegrantes)->updateOrInsert(
                        [
                            'ref_pessoa' => $integrante->ref_pessoa,
                            'ref_acao'   => $acao->id,
                        ],
                    );
                }

                foreach ($dadosOds->get($acao->id, []) as $ods) {
                    DB::table($pivotTableODS)->updateOrInsert(
                        [
                            'ref_acao' => $ods->ref_acao_extensionista,
                            'ref_ods'  => $ods->ref_ods,
                        ],
                    );
                }
            }
        });

        $this->atualizarSequence('ext_acao');
        $this->atualizarSequence($pivotTableIntegrantes);
        $this->atualizarSequence($pivotTableODS);
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
