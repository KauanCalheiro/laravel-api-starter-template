<?php

namespace Database\Seeders;

use App\Models\Estado;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class EstadoSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('data/estados.json');

        if (!File::exists($jsonPath)) {
            $this->command->warn("Arquivo estados.json não encontrado em: {$jsonPath}");
            return;
        }

        $data = json_decode(File::get($jsonPath), true);

        if (!$data) {
            $this->command->warn('Não foi possível decodificar o arquivo estados.json');
            return;
        }

        foreach ($data as $item) {
            if (!isset($item['id'], $item['nome'], $item['sigla'], $item['ref_pais'])) {
                continue;
            }

            Estado::updateOrCreate(
                ['id' => $item['id']],
                [
                    'nome'     => $item['nome'],
                    'sigla'    => $item['sigla'],
                    'ref_pais' => $item['ref_pais'],
                ],
            );
        }

        $maxId = Estado::max('id');
        if ($maxId) {
            $table    = (new Estado())->getTable();
            $sequence = "{$table}_id_seq";
            \DB::statement("SELECT setval('{$sequence}', {$maxId})");
        }
    }
}
