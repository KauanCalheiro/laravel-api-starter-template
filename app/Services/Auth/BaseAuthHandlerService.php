<?php

namespace App\Services\Auth;

use App\Http\Resources\JwtTokenResource;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\Guard;

abstract class BaseAuthHandlerService
{
    protected array $credentials;

    public function __construct($credentials)
    {
        $this->credentials = $credentials;
    }

    abstract public function login(): JwtTokenResource;
    abstract public function logout(): void;
    abstract public function refresh(): JwtTokenResource;

    public static function guard(): Guard|AuthManager
    {
        return auth();
    }
}
