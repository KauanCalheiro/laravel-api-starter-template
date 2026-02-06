<?php

namespace App\Auth\Jwt\Contracts;

use App\Http\Resources\JwtTokenResource;
use Tymon\JWTAuth\Contracts\JWTSubject;

interface TokenIssuer
{
    public function issueTokens(JWTSubject $user, array $claims = []): JwtTokenResource;

    public function refreshTokens(string $refreshToken): JwtTokenResource;

    public function revokeUserTokens(int $userId): void;
}
