<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_login_com_sucesso()
    {
        $user = User::first();

        $response = $this->post(route('auth.login'), [
            'login'    => (string) $user->id,
            'password' => env('MASTER_PASSWORD'),
        ]);

        if (! $response->isSuccessful()) {
            $this->fail($response->json()['message']);
        }

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
            ]);
    }

    public function test_logout_com_sucesso()
    {
        $user  = User::first();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => "Bearer {$token}"])
            ->post(route('auth.logout'));

        $response->assertStatus(200)
            ->assertJson([
                'message' => __('auth.logout.success'),
            ]);
    }

    public function test_retorna_dados_do_usuario_autenticado()
    {
        $user  = User::first();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => "Bearer {$token}"])
            ->get(route('auth.user'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'roles',
                'permissions',
            ])
            ->assertJson([
                'id'          => $user->id,
                'name'        => $user->name,
                'email'       => $user->email,
                'roles'       => $user->roles_list,
                'permissions' => $user->permissions_list,
            ]);
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
            'login'    => (string) $user->id,
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
