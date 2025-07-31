<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\State;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Tests\Contracts\CrudTestContract;
use Tests\Contracts\IncludeTestContract;
use Tests\Contracts\SearchTestContract;
use Tests\Helpers\JsonError;
use Tests\Helpers\JsonPagination;
use Tests\Helpers\JsonValidationError;
use Tests\TestCase;

class EstadoTest extends TestCase implements
    CrudTestContract,
    SearchTestContract,
    IncludeTestContract
{
    protected User $user;
    protected Model $model;
    protected string $table;
    protected string $route;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = new State();
        $this->route = 'estado';
        $this->table = $this->model->getTable();
        $this->user  = User::role('admin')->first();
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->user->createToken('test')->plainTextToken,
        ]);
    }

    public function test_listagem_com_paginacao()
    {
        $response = $this->getJson(route("{$this->route}.index"));

        $response->assertStatus(200)
            ->assertJsonStructure(JsonPagination::STRUCTURE);
    }

    public function test_listagem_com_paginacao_e_filtros()
    {
        $response = $this->getJson(
            route("{$this->route}.index") . '?filter[nome]=rio grande do sul&filter[sigla]=RS',
        );

        $response->assertStatus(200)
            ->assertJsonStructure(JsonPagination::STRUCTURE)
            ->assertJsonFragment([
                'id'    => 81,
                'nome'  => 'RIO GRANDE DO SUL',
                'sigla' => 'RS',
            ]);
    }

    public function test_listagem_com_search()
    {
        $response = $this->getJson(
            route("{$this->route}.index") . '?filter[search]=rio grande do sul',
        );

        $response->assertStatus(200)
            ->assertJsonStructure(JsonPagination::STRUCTURE)
            ->assertJsonFragment([
                'id'    => 81,
                'nome'  => 'RIO GRANDE DO SUL',
                'sigla' => 'RS',
            ]);
    }

    public function test_listagem_com_include()
    {
        $response = $this->getJson(
            route("{$this->route}.index") . '?include=pais',
        );

        $response->assertStatus(200)
            ->assertJsonStructure(JsonPagination::STRUCTURE)
            ->assertJsonFragment([
                'nome'  => 'BRASIL',
                'sigla' => 'BRA',
            ]);
    }

    public function test_erro_listagem_com_include_invalido()
    {
        $response = $this->getJson(
            route("{$this->route}.index") . '?include=invalido',
        );

        $response->assertStatus(400)
            ->assertJsonStructure(JsonError::STRUCTURE);
    }

    public function test_erro_listagem_com_filtros_incorretos()
    {
        $response = $this->getJson(
            route("{$this->route}.index") . '?filter[inexistente]=inexistente',
        );

        $response->assertStatus(400)
            ->assertJsonStructure(JsonError::STRUCTURE);
    }

    public function test_exibe_registro_existente()
    {
        $first = $this->model->first();

        if (!$first) {
            $this->markTestSkipped('No records found in the model.');
        }

        $response = $this->getJson(route("{$this->route}.show", $first->id));

        $response->assertStatus(200)
            ->assertJson($first->toArray());
    }

    public function test_exibe_registro_inexistente()
    {
        $response = $this->getJson(route("{$this->route}.show", -1));

        $response->assertStatus(404)
            ->assertJsonStructure(JsonError::STRUCTURE);
    }

    public function test_cria_registro()
    {
        $pais = Country::first();

        if (!$pais) {
            $this->markTestSkipped('No Pais records found.');
        }

        $data = [
            'nome'     => 'TEST ESTADO',
            'sigla'    => 'TE',
            'ref_pais' => (int)$pais->id,
        ];

        $response = $this->postJson(route("{$this->route}.store"), $data);

        $response->assertCreated()
            ->assertJsonFragment($data);

        $this->assertDatabaseHas($this->table, $data);
    }

    public function test_erro_cria_registro_com_campos_incorretos()
    {
        $data = [
            'nome'  => 'TEST ESTADO',
            'sigla' => 'TE',
            // 'ref_pais' is missing
        ];

        $response = $this->postJson(route("{$this->route}.store"), $data);

        $response->assertStatus(422)
            ->assertJsonStructure(JsonValidationError::STRUCTURE)
            ->assertJsonValidationErrors(['ref_pais']);
    }

    public function test_atualiza_registro()
    {
        $pais = Country::first();

        if (!$pais) {
            $this->markTestSkipped('No Pais records found.');
        }

        $data = [
            'nome'     => 'TEST ESTADO',
            'sigla'    => 'TE',
            'ref_pais' => (int)$pais->id,
        ];

        $estado = State::firstOrCreate(
            $data,
        );

        $this->assertDatabaseHas($this->table, $estado->toArray());

        $data['nome'] = 'TEST ESTADO ATUALIZADO';

        $response = $this->putJson(route("{$this->route}.update", $estado->id), $data);

        $response->assertStatus(200)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas($this->table, $data);
    }

    public function test_erro_atualiza_registro_com_campos_incorretos()
    {
        $pais = Country::first();

        if (!$pais) {
            $this->markTestSkipped('No Pais records found.');
        }

        $data = [
            'nome'     => 'TEST ESTADO',
            'sigla'    => 'TE',
            'ref_pais' => (int)$pais->id,
        ];

        $estado = State::firstOrCreate(
            $data,
        );

        $this->assertDatabaseHas($this->table, $estado->toArray());

        $data = [
            'sigla' => 'LOONG',
        ];

        $response = $this->putJson(route("{$this->route}.update", $estado->id), $data);

        $response->assertStatus(422)
            ->assertJsonStructure(JsonValidationError::STRUCTURE)
            ->assertJsonValidationErrors(['sigla']);
    }

    public function test_deleta_registro()
    {
        $estado = State::first();

        if (!$estado) {
            $this->markTestSkipped('No Estado records found.');
        }

        $response = $this->deleteJson(route("{$this->route}.destroy", $estado->id));

        $response->assertStatus(200);

        $this->assertSoftDeleted($this->table, $estado->toArray());
    }

    public function test_erro_deleta_registro_inexistente()
    {
        $response = $this->deleteJson(route("{$this->route}.destroy", -1));

        $response->assertStatus(404)
            ->assertJsonStructure(JsonError::STRUCTURE);
    }
}
