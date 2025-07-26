<?php

namespace Database\Seeders;

use App\Models\Pais;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class PaisSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('data/paises.json');

        if (!File::exists($jsonPath)) {
            $this->command->warn("Arquivo paises.json não encontrado em: {$jsonPath}");
            return;
        }

        $data = json_decode(File::get($jsonPath), true);

        if (!$data) {
            $this->command->warn('Não foi possível decodificar o arquivo paises.json');
            return;
        }

        foreach ($data as $item) {
            Pais::updateOrCreate(
                ['id' => $item['id']],
                [
                    'nome'  => $item['nome'],
                    'sigla' => $item['sigla'],
                ],
            );
        }
    }
}
