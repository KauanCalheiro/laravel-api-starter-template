<?php

namespace App\Auth\Jwt\Application;

use App\Auth\Jwt\Contracts\TokenRepository;
use App\Auth\Jwt\Contracts\TokenValidator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Payload;

class JwtTokenValidator implements TokenValidator
{
    public function __construct(
        private readonly JWTAuth $jwt,
        private readonly TokenRepository $repository,
    ) {
    }

    public function assertRefreshToken(string $token): Payload
    {
        try {
            $payload = $this->jwt->setToken($token)->getPayload();
        } catch (JWTException $e) {
            throw new TokenInvalidException($e->getMessage(), $e->getCode(), $e);
        }

        if ($payload->get('type') !== 'refresh') {
            throw new TokenInvalidException('Invalid refresh token type');
        }

        if ($this->repository->isRevoked($payload->get('jti'))) {
            throw new TokenInvalidException('Refresh token revoked');
        }

        return $payload;
    }
}
