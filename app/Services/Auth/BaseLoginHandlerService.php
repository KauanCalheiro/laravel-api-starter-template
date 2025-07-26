<?php

namespace App\Services\Auth;

use App\Contracts\LoginHandlerContract;
use App\Models\User;
use Exception;

abstract class BaseLoginHandlerService implements LoginHandlerContract
{
    protected array $credentials;
    protected ?User $authenticatable;

    public function __construct($credentials)
    {
        $this->credentials     = $credentials;
        $this->authenticatable = null;
    }

    final public function handleLogin(): array
    {
        return $this->validate()->login();
    }

    final protected function setUser(User $authenticatable): self
    {
        $this->authenticatable = $authenticatable;
        return $this;
    }

    final protected function authenticate(): array
    {
        if (empty($this->authenticatable)) {
            throw new Exception(__('auth.login.user_not_found'));
        }

        return [
            'token' => $this->authenticatable->createToken('auth_token', ['*'])->plainTextToken,
        ];
    }
}
