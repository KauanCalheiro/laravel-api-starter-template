<?php

namespace App\Services\Auth;

use App\Contracts\LoginHandlerContract;

class AuthService
{
    private const LOGIN_HANDLER = [
        'jwt' => JwtLoginService::class,
    ];

    private LoginHandlerContract $loginHandler;

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
}
