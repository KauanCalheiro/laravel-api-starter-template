<?php

namespace App\Services\Auth;

use App\Auth\Jwt\Contracts\AuthHandler;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthService
{
    private AuthHandler $loginHandler;

    public function __construct($credentials)
    {
        $handlers = config('auth_jwt.handlers');

        $handlerClass = $handlers[$credentials['driver']] ?? $handlers['jwt'];

        $this->loginHandler = app()->make($handlerClass, ['credentials' => $credentials]);
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
