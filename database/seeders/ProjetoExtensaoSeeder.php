<?php

namespace Database\Seeders;

use App\Models\ProjetoExtensao;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ProjetoExtensaoSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('data/projetos_extensao.json');

        if (!File::exists($jsonPath)) {
            $this->command->warn("Arquivo {$jsonPath} não encontrado.");
            return;
        }

        $projetos = json_decode(File::get($jsonPath), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('Erro ao decodificar JSON: ' . json_last_error_msg());
            return;
        }

        foreach ($projetos as $projeto) {
            // Limpar descrições que podem ter caracteres especiais ou tabs extras
            $descricao = trim(str_replace(['\\t', '\t'], '', $projeto['descricao']));

            ProjetoExtensao::updateOrCreate(
                ['id' => $projeto['id']],
                [
                    'descricao'       => $descricao,
                    'ref_coordenador' => $projeto['ref_coordenador'],
                    'dt_inicial'      => $projeto['dt_inicial'],
                    'dt_final'        => $projeto['dt_final'],
                ],
            );
        }
    }
}
