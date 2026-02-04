<?php

namespace App\Services\Auth;

use App\Http\Resources\JwtTokenResource;

abstract class BaseLoginHandlerService
{
    protected array $credentials;

    public function __construct($credentials)
    {
        $this->credentials = $credentials;
    }

    abstract protected function validate(): self;
    abstract protected function login(): JwtTokenResource;

    final public function handleLogin(): JwtTokenResource
    {
        return $this->validate()->login();
    }
}
