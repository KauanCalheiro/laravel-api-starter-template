<?php

namespace Tests\Feature;

use App\Models\Location\Country;
use App\Models\Location\State;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Contracts\CrudTestContract;
use Tests\Contracts\IncludeTestContract;
use Tests\Contracts\SearchTestContract;
use Tests\Helpers\Auth\JwtApiAuthenticatable;
use Tests\Helpers\JsonError;
use Tests\Helpers\JsonPagination;
use Tests\Helpers\JsonValidationError;
use Tests\TestCase;
use Tests\Trait\Authenticatable;

class StateTest extends TestCase implements
    CrudTestContract,
    SearchTestContract,
    IncludeTestContract
{
    use Authenticatable;
    use RefreshDatabase;

    protected Model $model;
    protected string $table;
    protected string $route;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->model = new State();
        $this->route = 'state';
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
                    'filter[name]' => 'rio grande do sul',
                    'filter[code]' => 'RS',
                ],
            ),
        );

        $response->assertStatus(200)
            ->assertJsonStructure(JsonPagination::STRUCTURE)
            ->assertJsonFragment([
                'name' => 'RIO GRANDE DO SUL',
                'code' => 'RS',
            ]);
    }

    public function test_listagem_com_search()
    {
        $response = $this->getJson(
            route(
                "{$this->route}.index",
                [
                    'filter[search]' => 'rio grande do sul',
                ],
            ),
        );

        $response->assertStatus(200)
            ->assertJsonStructure(JsonPagination::STRUCTURE)
            ->assertJsonFragment([
                'name' => 'RIO GRANDE DO SUL',
                'code' => 'RS',
            ]);
    }

    public function test_listagem_com_include()
    {
        $response = $this->getJson(
            route(
                "{$this->route}.index",
                [
                    'include' => 'country',
                ],
            ),
        );

        $response->assertStatus(200)
            ->assertJsonStructure(JsonPagination::STRUCTURE)
            ->assertJsonFragment([
                'name' => 'BRASIL',
                'code' => 'BRA',
            ]);
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
            route("{$this->route}.index", [
                'filter[inexistente]' => 'inexistente',
            ]),
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
        $country = Country::first();

        if (!$country) {
            $this->markTestSkipped('No Pais records found.');
        }

        $data = [
            'name'       => 'TEST ESTADO',
            'code'       => 'TE',
            'country_id' => (int)$country->id,
        ];

        $response = $this->postJson(route("{$this->route}.store"), $data);

        $response->assertCreated()
            ->assertJsonFragment($data);

        $this->assertDatabaseHas($this->table, $data);
    }

    public function test_erro_cria_registro_com_campos_incorretos()
    {
        $data = [
            'name' => 'TEST ESTADO',
            'code' => 'TE',
            // 'country_id' is missing
        ];

        $response = $this->postJson(route("{$this->route}.store"), $data);

        $response->assertStatus(422)
            ->assertJsonStructure(JsonValidationError::STRUCTURE)
            ->assertJsonValidationErrors(['country_id']);
    }

    public function test_atualiza_registro()
    {
        $country = Country::first();

        if (!$country) {
            $this->markTestSkipped('No Pais records found.');
        }

        $data = [
            'name'       => 'TEST ESTADO',
            'code'       => 'TE',
            'country_id' => (int)$country->id,
        ];

        $estado = State::firstOrCreate(
            $data,
        );

        $this->assertDatabaseHas($this->table, $estado->toArray());

        $data['name'] = 'TEST ESTADO ATUALIZADO';

        $response = $this->putJson(route("{$this->route}.update", $estado->id), $data);

        $response->assertStatus(200)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas($this->table, $data);
    }

    public function test_erro_atualiza_registro_com_campos_incorretos()
    {
        $country = Country::first();

        if (!$country) {
            $this->markTestSkipped('No Pais records found.');
        }

        $data = [
            'name'       => 'TEST ESTADO',
            'code'       => 'TE',
            'country_id' => (int)$country->id,
        ];

        $estado = State::firstOrCreate(
            $data,
        );

        $this->assertDatabaseHas($this->table, $estado->toArray());

        $data = [
            'code' => 'LOONG',
        ];

        $response = $this->putJson(route("{$this->route}.update", $estado->id), $data);

        $response->assertStatus(422)
            ->assertJsonStructure(JsonValidationError::STRUCTURE)
            ->assertJsonValidationErrors(['code']);
    }

    public function test_deleta_registro()
    {
        $estado = State::first();

        if (!$estado) {
            $this->markTestSkipped('No Estado records found.');
        }

        $response = $this->deleteJson(route("{$this->route}.destroy", $estado->id));

        $response->assertStatus(204);

        $this->assertSoftDeleted($this->table, $estado->toArray());
    }

    public function test_erro_deleta_registro_inexistente()
    {
        $response = $this->deleteJson(route("{$this->route}.destroy", -1));

        $response->assertStatus(404)
            ->assertJsonStructure(JsonError::STRUCTURE);
    }
}
