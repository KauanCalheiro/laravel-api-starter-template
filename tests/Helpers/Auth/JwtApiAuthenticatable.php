<?php

namespace Tests\Helpers\Auth;

use Tymon\JWTAuth\Facades\JWTAuth;

class JwtApiAuthenticatable extends BaseAuthenticatable
{
    public function autenticate(string|null $role = null): void
    {
        $this->user($role);

        $this->test->withHeaders([
            'Authorization' => "Bearer {$this->getUserJwtToken()}",
        ]);
    }

    private function getUserJwtToken(): string
    {
        return JWTAuth::fromUser($this->user);
    }
}
