<?php

namespace App\Services\Auth;

use App\Contracts\LoginHandlerContract;
use App\Models\User;
use Exception;

abstract class BaseLoginHandlerService implements LoginHandlerContract
{
    protected array $credentials;

    public function __construct($credentials)
    {
        $this->credentials = $credentials;
    }

    final public function handleLogin(): array
    {
        return $this->validate()->login();
    }

    final protected function authenticate(User $authenticatable): array
    {
        if (empty($authenticatable)) {
            throw new Exception(__('auth.login.user_not_found'));
        }

        return [
            'token' => $authenticatable->createToken('auth_token', ['*'])->plainTextToken,
        ];
    }
}
