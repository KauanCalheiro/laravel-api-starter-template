<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Tests\Contracts\CrudTestContract;
use Tests\Contracts\SearchTestContract;
use Tests\Helpers\JsonError;
use Tests\Helpers\JsonPagination;
use Tests\Helpers\JsonValidationError;
use Tests\TestCase;

class PaisTest extends TestCase implements CrudTestContract, SearchTestContract
{
    protected User $user;
    protected Model $model;
    protected string $table;
    protected string $route;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = new Country();
        $this->route = 'pais';
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
            route("{$this->route}.index") . '?filter[nome]=BRASIL&filter[sigla]=BRA',
        );

        $response->assertStatus(200)
            ->assertJsonStructure(JsonPagination::STRUCTURE)
            ->assertJsonFragment([
                'id'    => 10,
                'nome'  => 'BRASIL',
                'sigla' => 'BRA',
            ]);
    }

    public function test_listagem_com_search()
    {
        $response = $this->getJson(
            route("{$this->route}.index") . '?filter[search]=BRASIL',
        );

        $response->assertStatus(200)
            ->assertJsonStructure(JsonPagination::STRUCTURE)
            ->assertJsonFragment([
                'id'    => 10,
                'nome'  => 'BRASIL',
                'sigla' => 'BRA',
            ]);
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
        $data = ['nome' => 'URUGUAY', 'sigla' => 'URY'];

        $response = $this->postJson(route("{$this->route}.store"), $data);

        $response->assertCreated()
                 ->assertJsonFragment($data);

        $this->assertDatabaseHas($this->table, $data);
    }

    public function test_erro_cria_registro_com_campos_incorretos()
    {
        $data = ['nome' => 'URUGUAY', 'sigla' => 'TOO_LONG'];

        $response = $this->postJson(route("{$this->route}.store"), $data);

        $response->assertStatus(422)
            ->assertJsonStructure(JsonValidationError::STRUCTURE)
            ->assertJsonValidationErrors(['sigla']);
    }

    public function test_atualiza_registro()
    {
        $data = [
            'nome'  => 'TEST PAIS',
            'sigla' => 'TP',
        ];

        $pais = Country::firstOrCreate(
            $data,
        );

        $response = $this->putJson(route("{$this->route}.update", $pais->id), $data);

        $response->assertStatus(200)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas($this->table, $data);
    }

    public function test_erro_atualiza_registro_com_campos_incorretos()
    {
        $data = ['nome' => 'URUGUAY', 'sigla' => 'TOO_LONG'];

        $pais = Country::firstOrCreate(
            ['nome' => 'URUGUAY', 'sigla' => 'URY'],
        );

        $response = $this->putJson(route("{$this->route}.update", $pais->id), $data);

        $response->assertStatus(422)
            ->assertJsonStructure(JsonValidationError::STRUCTURE)
            ->assertJsonValidationErrors(['sigla']);
    }

    public function test_deleta_registro()
    {
        $pais = Country::first();

        if (!$pais) {
            $this->markTestSkipped('No Pais records found.');
        }

        $response = $this->deleteJson(route("{$this->route}.destroy", $pais->id));

        $response->assertStatus(200);

        $this->assertSoftDeleted($this->table, $pais->toArray());
    }

    public function test_erro_deleta_registro_inexistente()
    {
        $response = $this->deleteJson(route("{$this->route}.destroy", -1));

        $response->assertStatus(404)
            ->assertJsonStructure(JsonError::STRUCTURE);
    }
}
