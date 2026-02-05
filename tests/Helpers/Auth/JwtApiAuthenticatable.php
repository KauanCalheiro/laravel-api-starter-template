<?php

namespace Tests\Helpers\Auth;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtApiAuthenticatable extends BaseAuthenticatable
{
    public function authenticate(string|null $role = null): User
    {
        $this->user($role);

        $this->test->withHeaders([
            'Authorization' => "Bearer {$this->getUserJwtToken()}",
        ]);

        return $this->user;
    }

    private function getUserJwtToken(): string
    {
        return JWTAuth::fromUser($this->user);
    }
}
