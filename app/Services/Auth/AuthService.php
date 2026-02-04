<?php

namespace App\Services\Auth;

use Illuminate\Auth\AuthManager;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;

class AuthService
{
    private const LOGIN_HANDLER = [
        'jwt' => JwtLoginService::class,
    ];

    private BaseLoginHandlerService $loginHandler;

    public function __construct($credentials)
    {
        $handlerClass       = self::LOGIN_HANDLER[$credentials['driver']];
        $this->loginHandler = new $handlerClass($credentials);
    }

    public static function make($credentials): self
    {
        return new self($credentials);
    }

    public function handleLogin()
    {
        return $this->loginHandler->handleLogin();
    }

    public static function authResolver(): JWTGuard|AuthManager
    {
        return auth();
    }
}
