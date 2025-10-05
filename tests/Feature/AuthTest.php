<?php

namespace Tests\Feature;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthTest extends TestCase
{
    use RefreshDatabase;

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
}
