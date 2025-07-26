<?php

namespace Database\Seeders;

use App\Models\ProgramaExtensao;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ProgramaExtensaoSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('data/programas_extensao.json');

        if (!File::exists($jsonPath)) {
            $this->command->warn("Arquivo {$jsonPath} não encontrado.");
            return;
        }

        $programas = json_decode(File::get($jsonPath), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('Erro ao decodificar JSON: ' . json_last_error_msg());
            return;
        }

        foreach ($programas as $programa) {
            ProgramaExtensao::updateOrCreate(
                ['id' => $programa['id']],
                [
                    'nome'      => $programa['nome'],
                    'descricao' => $programa['descricao'],
                    'fl_ativo'  => $programa['fl_ativo'],
                ],
            );
        }
    }
}
