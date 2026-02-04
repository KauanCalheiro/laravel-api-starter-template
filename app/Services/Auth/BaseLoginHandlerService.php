<?php

namespace App\Services\Auth;

use App\Contracts\LoginHandlerContract;
use App\Http\Resources\JwtTokenResource;
use App\Models\User;
use Auth;
use Exception;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

abstract class BaseLoginHandlerService implements LoginHandlerContract
{
    protected array $credentials;

    public function __construct($credentials)
    {
        $this->credentials = $credentials;
    }

    final public function handleLogin(): JwtTokenResource
    {
        return $this->validate()->login();
    }

    final protected function authenticate(User $authenticatable): string
    {
        if (empty($authenticatable)) {
            throw new Exception(__('auth.login.user_not_found'));
        }

        Auth::setUser($authenticatable);

        return JWTAuth::fromUser($authenticatable);
    }
}
