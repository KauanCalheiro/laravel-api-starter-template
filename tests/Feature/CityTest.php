<?php

namespace Tests\Feature;

use App\Models\Location\City;
use App\Models\Location\State;
use Illuminate\Database\Eloquent\Model;
use Tests\Contracts\CrudTestContract;
use Tests\Contracts\IncludeTestContract;
use Tests\Contracts\SearchTestContract;
use Tests\Helpers\Auth\JwtApiAuthenticatable;
use Tests\Helpers\JsonError;
use Tests\Helpers\JsonPagination;
use Tests\Helpers\JsonValidationError;
use Tests\TestCase;
use Tests\Trait\Authenticatable;

class CityTest extends TestCase implements
    CrudTestContract,
    SearchTestContract,
    IncludeTestContract
{
    use Authenticatable;

    protected Model $model;
    protected string $table;
    protected string $route;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = new City();
        $this->route = 'city';
        $this->table = $this->model->getTable();

        $this->authenticate(JwtApiAuthenticatable::class);
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
            route(
                "{$this->route}.index",
                [
                    'filter[name]'       => 'lajeado',
                    'filter[state.code]' => 'rs',
                ],
            ),
        );

        $response->assertStatus(200)
            ->assertJsonStructure(JsonPagination::STRUCTURE);
    }

    public function test_listagem_com_search()
    {
        $response = $this->getJson(
            route(
                "{$this->route}.index",
                [
                    'filter[search]' => 'RS',
                ],
            ),
        );

        $response->assertStatus(200)
            ->assertJsonStructure(JsonPagination::STRUCTURE);
    }

    public function test_listagem_com_include()
    {
        $response = $this->getJson(
            route(
                "{$this->route}.index",
                [
                    'include' => 'state',
                ],
            ),
        );

        $response->assertStatus(200)
            ->assertJsonStructure(JsonPagination::STRUCTURE);
    }

    public function test_erro_listagem_com_include_invalido()
    {
        $response = $this->getJson(
            route(
                "{$this->route}.index",
                [
                    'include' => 'invalido',
                ],
            ),
        );

        $response->assertStatus(400)
            ->assertJsonStructure(JsonError::STRUCTURE);
    }

    public function test_erro_listagem_com_filtros_incorretos()
    {
        $response = $this->getJson(
            route(
                "{$this->route}.index",
                [
                    'filter[inexistente]' => 'inexistente',
                ],
            ),
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
        $state = State::first();

        if (!$state) {
            $this->markTestSkipped('No Estado records found.');
        }

        $data = [
            'name'     => 'TEST CIDADE',
            'state_id' => (int)$state->id,
        ];

        $response = $this->postJson(route("{$this->route}.store"), $data);

        $response->assertCreated()
            ->assertJsonFragment($data);

        $this->assertDatabaseHas($this->table, $data);
    }

    public function test_erro_cria_registro_com_campos_incorretos()
    {
        $data = [
            'name' => 'TEST CIDADE',
        ];

        $response = $this->postJson(route("{$this->route}.store"), $data);

        $response->assertStatus(422)
            ->assertJsonStructure(JsonValidationError::STRUCTURE)
            ->assertJsonValidationErrors(['state_id']);
    }

    public function test_atualiza_registro()
    {
        $state = State::first();

        if (!$state) {
            $this->markTestSkipped('No Estado records found.');
        }

        $data = [
            'name'     => 'TEST CIDADE',
            'state_id' => (int)$state->id,
        ];

        $state = City::firstOrCreate(
            $data,
        );

        $this->assertDatabaseHas($this->table, $state->toArray());

        $data['name'] = 'TEST CIDADE ATUALIZADO';

        $response = $this->putJson(route("{$this->route}.update", $state->id), $data);

        $response->assertStatus(200)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas($this->table, $data);
    }

    public function test_erro_atualiza_registro_com_campos_incorretos()
    {
        $state = State::first();

        if (!$state) {
            $this->markTestSkipped('No Estado records found.');
        }

        $data = [
            'name'     => 'TEST CIDADE',
            'state_id' => (int)$state->id,
        ];

        $state = City::firstOrCreate(
            $data,
        );

        $this->assertDatabaseHas($this->table, $state->toArray());

        $data = [
            'state_id' => -1,
        ];

        $response = $this->putJson(route("{$this->route}.update", $state->id), $data);

        $response->assertStatus(422)
            ->assertJsonStructure(JsonValidationError::STRUCTURE)
            ->assertJsonValidationErrors(['state_id']);
    }

    public function test_deleta_registro()
    {
        $city = City::first();

        if (!$city) {
            $this->markTestSkipped('No Cidade records found.');
        }

        $response = $this->deleteJson(route("{$this->route}.destroy", $city->id));

        $response->assertStatus(204);

        $this->assertSoftDeleted($this->table, $city->toArray());
    }

    public function test_erro_deleta_registro_inexistente()
    {
        $response = $this->deleteJson(route("{$this->route}.destroy", -1));

        $response->assertStatus(404)
            ->assertJsonStructure(JsonError::STRUCTURE);
    }
}
