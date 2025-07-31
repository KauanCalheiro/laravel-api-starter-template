<?php

namespace Tests\Feature;

use App\Models\City;
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

class CidadeTest extends TestCase implements
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

        $this->model = new City();
        $this->route = 'cidade';
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
            route("{$this->route}.index") . '?filter[nome]=lajeado&filter[estado.sigla]=rs',
        );

        $response->assertStatus(200)
            ->assertJsonStructure(JsonPagination::STRUCTURE);
    }

    public function test_listagem_com_search()
    {
        $response = $this->getJson(
            route("{$this->route}.index") . '?filter[search]=RS',
        );

        $response->assertStatus(200)
            ->assertJsonStructure(JsonPagination::STRUCTURE);
    }

    public function test_listagem_com_include()
    {
        $response = $this->getJson(
            route("{$this->route}.index") . '?include=estado',
        );

        $response->assertStatus(200)
            ->assertJsonStructure(JsonPagination::STRUCTURE);
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
        $estado = State::first();

        if (!$estado) {
            $this->markTestSkipped('No Estado records found.');
        }

        $data = [
            'nome'       => 'TEST CIDADE',
            'ref_estado' => (int)$estado->id,
        ];

        $response = $this->postJson(route("{$this->route}.store"), $data);

        $response->assertCreated()
            ->assertJsonFragment($data);

        $this->assertDatabaseHas($this->table, $data);
    }

    public function test_erro_cria_registro_com_campos_incorretos()
    {
        $data = [
            'nome' => 'TEST CIDADE',
        ];

        $response = $this->postJson(route("{$this->route}.store"), $data);

        $response->assertStatus(422)
            ->assertJsonStructure(JsonValidationError::STRUCTURE)
            ->assertJsonValidationErrors(['ref_estado']);
    }

    public function test_atualiza_registro()
    {
        $estado = State::first();

        if (!$estado) {
            $this->markTestSkipped('No Estado records found.');
        }

        $data = [
            'nome'       => 'TEST CIDADE',
            'ref_estado' => (int)$estado->id,
        ];

        $estado = City::firstOrCreate(
            $data,
        );

        $this->assertDatabaseHas($this->table, $estado->toArray());

        $data['nome'] = 'TEST CIDADE ATUALIZADO';

        $response = $this->putJson(route("{$this->route}.update", $estado->id), $data);

        $response->assertStatus(200)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas($this->table, $data);
    }

    public function test_erro_atualiza_registro_com_campos_incorretos()
    {
        $estado = State::first();

        if (!$estado) {
            $this->markTestSkipped('No Estado records found.');
        }

        $data = [
            'nome'       => 'TEST CIDADE',
            'ref_estado' => (int)$estado->id,
        ];

        $estado = City::firstOrCreate(
            $data,
        );

        $this->assertDatabaseHas($this->table, $estado->toArray());

        $data = [
            'ref_estado' => -1,
        ];

        $response = $this->putJson(route("{$this->route}.update", $estado->id), $data);

        $response->assertStatus(422)
            ->assertJsonStructure(JsonValidationError::STRUCTURE)
            ->assertJsonValidationErrors(['ref_estado']);
    }

    public function test_deleta_registro()
    {
        $cidade = City::first();

        if (!$cidade) {
            $this->markTestSkipped('No Cidade records found.');
        }

        $response = $this->deleteJson(route("{$this->route}.destroy", $cidade->id));

        $response->assertStatus(200);

        $this->assertSoftDeleted($this->table, $cidade->toArray());
    }

    public function test_erro_deleta_registro_inexistente()
    {
        $response = $this->deleteJson(route("{$this->route}.destroy", -1));

        $response->assertStatus(404)
            ->assertJsonStructure(JsonError::STRUCTURE);
    }
}
