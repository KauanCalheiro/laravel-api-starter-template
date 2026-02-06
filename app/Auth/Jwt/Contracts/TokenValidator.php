<?php

namespace App\Auth\Jwt\Contracts;

use Tymon\JWTAuth\Payload;

interface TokenValidator
{
    public function assertRefreshToken(string $token): Payload;
}
