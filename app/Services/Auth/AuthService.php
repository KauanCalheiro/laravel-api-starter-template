<?php

namespace App\Services\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthService
{
    private const LOGIN_HANDLER = [
        'jwt' => JwtAuthService::class,
    ];

    private BaseAuthHandlerService $loginHandler;

    public function __construct($credentials)
    {
        $handlerClass       = self::LOGIN_HANDLER[$credentials['driver']];
        $this->loginHandler = new $handlerClass($credentials);
    }

    public static function make($credentials): self
    {
        return new self($credentials);
    }

    public function login(): JsonResource
    {
        return $this->loginHandler->login();
    }

    public function logout(): void
    {
        $this->loginHandler->logout();
    }

    public function refresh(): JsonResource
    {
        return $this->loginHandler->refresh();
    }
}
