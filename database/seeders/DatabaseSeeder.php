<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            OdsSeeder::class,
            PaisSeeder::class,
            EstadoSeeder::class,
            CidadeSeeder::class,
            PlanejamentoAcaoSeeder::class,
            ProgramaExtensaoSeeder::class,
            ProjetoExtensaoSeeder::class,
            AcaoSeeder::class,
            EvidenciaSeeder::class,
        ]);
    }
}
