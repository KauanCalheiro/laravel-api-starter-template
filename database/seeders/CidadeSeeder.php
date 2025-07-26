<?php

namespace Database\Seeders;

use App\Models\Cidade;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CidadeSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('data/cidades.json');

        if (!File::exists($jsonPath)) {
            $this->command->warn("Arquivo cidades.json não encontrado em: {$jsonPath}");
            return;
        }

        $data = json_decode(File::get($jsonPath), true);

        if (!$data) {
            $this->command->warn('Não foi possível decodificar o arquivo cidades.json');
            return;
        }

        $chunks = array_chunk($data, 1000);

        foreach ($chunks as $chunk) {
            foreach ($chunk as &$item) {
                $item['created_at'] = now();
                $item['updated_at'] = now();
            }
            Cidade::upsert($chunk, ['id'], ['nome', 'ref_estado', 'updated_at']);
        }

        $maxId = Cidade::max('id');
        if ($maxId) {
            $table    = (new Cidade())->getTable();
            $sequence = "{$table}_id_seq";
            \DB::statement("SELECT setval('{$sequence}', {$maxId})");
        }
    }
}
