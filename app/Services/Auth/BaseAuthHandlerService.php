<?php

namespace App\Services\Auth;

use App\Auth\Jwt\Contracts\AuthHandler;
use App\Auth\Jwt\Contracts\TokenIssuer;
use App\Http\Resources\JwtTokenResource;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\Guard;

abstract class BaseAuthHandlerService implements AuthHandler
{
    protected array $credentials;

    public function __construct(array $credentials)
    {
        $this->credentials = $credentials;
    }

    abstract public function login(): JwtTokenResource;
    abstract public function logout(): void;
    abstract public function refresh(): JwtTokenResource;

    public static function guard(): Guard|AuthManager|TokenIssuer
    {
        return auth('api');
    }
}
