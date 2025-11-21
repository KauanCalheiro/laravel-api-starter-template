<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Factories\UserFactory;
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

class UserTest extends TestCase implements
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

        $this->model = new User();
        $this->route = 'user';
        $this->table = $this->model->getTable();

        $this->authenticate(JwtApiAuthenticatable::class);
    }

    public function test_atualiza_registro()
    {
        $user = UserFactory::new()->create();

        $this->assertDatabaseHas($this->table, $user->toArray());

        $data = [
            'name'  => 'Nome Atualizado',
            'email' => 'email@atualizado.com',
        ];

        $response = $this->putJson(route("{$this->route}.update", $user->id), $data);

        $response->assertStatus(200)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas($this->table, $data);
    }

    public function test_cria_registro()
    {
        $user = UserFactory::new()->makeOne();

        $user->password_confirmation = $user->password;

        $data = [
            ...$user->toArray(),
            'password' => $user->password,
        ];

        $response = $this->postJson(route("{$this->route}.store"), $data);

        $assert = $user->only(['name', 'email']);

        $response->assertStatus(201)
            ->assertJsonFragment($assert);

        $this->assertDatabaseHas($this->table, $assert);
    }

    public function test_deleta_registro()
    {
        $user = UserFactory::new()->create();

        $this->assertDatabaseHas($this->table, $user->toArray());

        $response = $this->deleteJson(route("{$this->route}.destroy", $user->id));

        $response->assertStatus(204);

        $this->assertSoftDeleted($this->table, $user->toArray());
    }

    public function test_erro_atualiza_registro_com_campos_incorretos()
    {
        $user = UserFactory::new()->create();

        $this->assertDatabaseHas($this->table, $user->toArray());

        $response = $this->putJson(route("{$this->route}.update", $user->id), []);

        $response->assertStatus(422)
        ->assertJsonStructure(JsonValidationError::STRUCTURE)
        ->assertJsonValidationErrors(['name', 'email']);
    }

    public function test_erro_cria_registro_com_campos_incorretos()
    {
        $response = $this->postJson(route("{$this->route}.store"), []);

        $response->assertStatus(422)
            ->assertJsonStructure(JsonValidationError::STRUCTURE)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function test_erro_deleta_registro_inexistente()
    {
        $response = $this->deleteJson(route("{$this->route}.destroy", -999999));

        $response->assertStatus(404)
            ->assertJsonStructure(JsonError::STRUCTURE);
    }

    public function test_erro_listagem_com_filtros_incorretos()
    {
        $response = $this->getJson(route("{$this->route}.index", [
            'filter[unknown_field]' => 'some_value',
        ]));

        $response->assertStatus(400)
            ->assertJsonStructure(JsonError::STRUCTURE);
    }

    public function test_exibe_registro_existente()
    {
        $user = UserFactory::new()->create();

        $this->assertDatabaseHas($this->table, $user->toArray());

        $response = $this->getJson(route("{$this->route}.show", $user->id));

        $response->assertStatus(200)
            ->assertJson($user->only(['id', 'name', 'email']));
    }

    public function test_exibe_registro_inexistente()
    {
        $response = $this->getJson(route("{$this->route}.show", -9999));

        $response->assertStatus(404)
            ->assertJsonStructure(JsonError::STRUCTURE);
    }

    public function test_listagem_com_paginacao()
    {
        $response = $this->getJson(route("{$this->route}.index"));

        $response->assertStatus(200)
            ->assertJsonStructure(JsonPagination::STRUCTURE);
    }

    public function test_listagem_com_paginacao_e_filtros()
    {
        $response = $this->getJson(route("{$this->route}.index", [
            'filter[name]' => 'Admin',
        ]));

        $response->assertStatus(200)
            ->assertJsonStructure(JsonPagination::STRUCTURE);
    }

    public function test_listagem_com_search()
    {
        $response = $this->getJson(route("{$this->route}.index", [
            'filter[search]' => 'Admin',
        ]));

        $response->assertStatus(200)
            ->assertJsonStructure(JsonPagination::STRUCTURE);
    }

    public function test_erro_listagem_com_include_invalido()
    {
        $response = $this->getJson(route("{$this->route}.index", [
            'include' => 'invalid_relation',
        ]));

        $response->assertStatus(400)
            ->assertJsonStructure(JsonError::STRUCTURE);
    }

    public function test_listagem_com_include()
    {
        $response = $this->getJson(route("{$this->route}.index", [
            'include' => 'roles',
        ]));

        $response->assertStatus(200)
            ->assertJsonStructure(JsonPagination::STRUCTURE);
    }
}
