<?php

namespace Tests\Feature;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Auth\JwtAuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
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
                'access_token',
                'token_type',
                'expires_in',
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

    public function test_refresh_token_com_sucesso()
    {
        $user = User::first();

        $resource = JwtAuthService::guard()->login($user)->toArray(new Request());

        $response = $this->post(route('auth.refresh'), [
            'refresh_token' => $resource['refresh_token'],
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'refresh_token',
                'token_type',
                'expires_in',
            ]);

        $this->assertNotSame($resource['access_token'], $response->json()['access_token']);
        $this->assertNotSame($resource['refresh_token'], $response->json()['refresh_token']);
    }

    public function test_retorna_dados_do_usuario_autenticado()
    {
        $user = User::first();

        $response = $this->withHeaders(['Authorization' => "Bearer {$this->generateJwtToken($user)}"])
            ->get(route('auth.me'));

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
        $response = $this->get(route('auth.me'));

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
