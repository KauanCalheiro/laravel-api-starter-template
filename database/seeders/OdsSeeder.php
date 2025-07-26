<?php

namespace Database\Seeders;

use App\Models\Ods;
use Illuminate\Database\Seeder;

class OdsSeeder extends Seeder
{
    public function run(): void
    {
        $ods = [
            ['id' => 1, 'nome' => 'Erradicação da Pobreza', 'descricao' => 'Acabar com a pobreza em todas as suas formas, em todos os lugares.'],
            ['id' => 2, 'nome' => 'Fome Zero e Agricultura Sustentável', 'descricao' => 'Acabar com a fome, alcançar a segurança alimentar e melhoria da nutrição e promover a agricultura sustentável.'],
            ['id' => 3, 'nome' => 'Saúde e Bem-estar', 'descricao' => 'Assegurar vida saudável e promover o bem-estar de todos, em todas as idades.'],
            ['id' => 4, 'nome' => 'Educação de Qualidade', 'descricao' => 'Assegurar educação de qualidade inclusiva e equitativa, e promover oportunidades de aprendizagem ao longo da vida para todos.'],
            ['id' => 5, 'nome' => 'Igualdade de Gênero', 'descricao' => 'Alcançar a igualdade de gênero e empoderar todas as mulheres e meninas.'],
            ['id' => 6, 'nome' => 'Água Potável e Saneamento', 'descricao' => 'Garantir a disponibilidade e gestão sustentável da água e do saneamento para todos.'],
            ['id' => 7, 'nome' => 'Energia Acessível e Limpa', 'descricao' => 'Assegurar acesso à energia limpa, acessível e confiável para todos.'],
            ['id' => 8, 'nome' => 'Trabalho Decente e Crescimento Econômico', 'descricao' => 'Promover o crescimento econômico inclusivo e sustentável, o emprego pleno e produtivo e o trabalho decente para todos.'],
            ['id' => 9, 'nome' => 'Indústria, Inovação e Infraestrutura', 'descricao' => 'Construir infraestrutura resiliente, promover a industrialização inclusiva e sustentável e fomentar a inovação.'],
            ['id' => 10, 'nome' => 'Redução das Desigualdades', 'descricao' => 'Reduzir a desigualdade dentro dos países e entre eles.'],
            ['id' => 11, 'nome' => 'Cidades e Comunidades Sustentáveis', 'descricao' => 'Tornar as cidades e os assentamentos humanos inclusivos, seguros, resilientes e sustentáveis.'],
            ['id' => 12, 'nome' => 'Produção e Consumo Responsáveis', 'descricao' => 'Assegurar padrões de produção e consumo sustentáveis.'],
            ['id' => 13, 'nome' => 'Ação Climática', 'descricao' => 'Tomar medidas urgentes para combater a mudança climática e seus impactos.'],
            ['id' => 14, 'nome' => 'Vida Marinha', 'descricao' => 'Conservar e usar de forma sustentável os oceanos, mares e recursos marinhos para o desenvolvimento sustentável.'],
            ['id' => 15, 'nome' => 'Vida Terrestre', 'descricao' => 'Proteger, restaurar e promover o uso sustentável dos ecossistemas terrestres, gerir de forma sustentável as florestas, combater a desertificação e deter a perda de biodiversidade.'],
            ['id' => 16, 'nome' => 'Paz, Justiça e Instituições Fortes', 'descricao' => 'Promover sociedades pacíficas e inclusivas para o desenvolvimento sustentável, proporcionar acesso à justiça para todos e construir instituições eficazes, responsáveis e inclusivas em todos os níveis.'],
            ['id' => 17, 'nome' => 'Parcerias para as Metas', 'descricao' => 'Revitalizar a parceria global para o desenvolvimento sustentável.'],
        ];

        foreach ($ods as $item) {
            Ods::updateOrCreate(['id' => $item['id']], $item);
        }
    }
}
