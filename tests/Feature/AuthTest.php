<?php

namespace Tests\Feature;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\Auth\JwtApiAuthenticatable;
use Tests\Helpers\JsonError;
use Tests\TestCase;
use Tests\Trait\Authenticatable;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    use Authenticatable;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    private function generateJwtToken(User $user)
    {
        return JWTAuth::fromUser($user);
    }

    public function test_login_com_sucesso()
    {
        $user = User::first();

        $response = $this->post(route('auth.login'), [
            'email'    => $user->email,
            'password' => env('MASTER_PASSWORD'),
        ]);

        if (!$response->isSuccessful()) {
            $this->fail($response->json()['message']);
        }

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
            ]);
    }

    public function test_logout_com_sucesso()
    {
        $user = User::first();

        $response = $this->withHeaders(['Authorization' => "Bearer {$this->generateJwtToken($user)}"])
            ->post(route('auth.logout'));

        $response->assertStatus(200)
            ->assertJson([
                'message' => __('auth.logout.success'),
            ]);
    }

    public function test_retorna_dados_do_usuario_autenticado()
    {
        $user = User::first();

        $response = $this->withHeaders(['Authorization' => "Bearer {$this->generateJwtToken($user)}"])
            ->get(route('auth.user'));

        $userResource = new UserResource($user);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'active_role',
                'roles',
                'permissions',
            ])
            ->assertJson($userResource->response()->getData(true));
    }

    public function test_erro_usuario_nao_autenticado()
    {
        $response = $this->get(route('auth.user'));

        $response->assertStatus(401)
            ->assertJson([
                'error' => __('auth.unauthenticated'),
            ]);
    }

    public function test_login_falha_senha_incorreta()
    {
        $user = User::first();

        $response = $this->post(route('auth.login'), [
            'email'    => $user->email,
            'password' => 'wrong_password',
        ]);

        $response->assertStatus(401)
            ->assertJsonStructure([
                'error',
            ]);

        $this->assertStringContainsString(
            __('auth.login.failed_with_message', ['message' => '']),
            $response->json()['error'],
        );
    }

    public function test_impersonation()
    {
        $this->authenticate(JwtApiAuthenticatable::class);

        $user = User::whereKeyNot($this->user->getKey())->first();

        $response = $this->get(route('auth.impersonate', $user->getKey()));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
            ]);
    }

    public function test_impersonation_unauthenticated()
    {
        $response = $this->get(route('auth.impersonate', User::first()->getKey()));

        $response->assertStatus(401)
            ->assertJson([
                'error' => __('auth.unauthenticated'),
            ]);
    }

    public function test_self_impersonation_error()
    {
        $this->authenticate(JwtApiAuthenticatable::class);

        $response = $this->get(route('auth.impersonate', $this->user->getKey()));

        $response->assertJsonStructure(JsonError::STRUCTURE)
            ->assertJsonFragment([
                'error' => __('impersonate.cannot.impersonate_yourself'),
            ]);
    }

    public function test_impersonation_nested_error()
    {
        $this->authenticate(JwtApiAuthenticatable::class);

        $userToImpersonate = User::whereKeyNot($this->user->getKey())->first();

        $response = $this->get(route('auth.impersonate', $userToImpersonate->getKey()));

        $token = $response->json()['token'];

        $anotherUser = User::whereNotIn('id', [$this->user->getKey(), $userToImpersonate->getKey()])->first();

        $response = $this->withHeaders(['Authorization' => "Bearer {$token}"])
            ->get(route('auth.impersonate', $anotherUser->getKey()));

        $response->assertJsonStructure(JsonError::STRUCTURE)
            ->assertJsonFragment([
                'error' => __('impersonate.already_impersonating'),
            ]);
    }

    public function test_unimpersonation()
    {
        $this->authenticate(JwtApiAuthenticatable::class);

        $userToImpersonate = User::whereKeyNot($this->user->getKey())->first();

        $response = $this->get(route('auth.impersonate', $userToImpersonate->getKey()));

        if (!$response->isSuccessful()) {
            $this->fail($response->json()['message']);
        }

        $token = $response->json()['token'];

        $response = $this->withHeaders(['Authorization' => "Bearer {$token}"])
            ->get(route('auth.unimpersonate'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
            ]);
    }

    public function test_unimpersonation_without_impersonation_error()
    {
        $this->authenticate(JwtApiAuthenticatable::class);

        $response = $this->get(route('auth.unimpersonate'));

        $response->assertJsonStructure(JsonError::STRUCTURE)
            ->assertJsonFragment([
                'error' => __('impersonate.not_impersonating_anyone'),
            ]);
    }
}
