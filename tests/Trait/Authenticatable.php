<?php

namespace Tests\Trait;

use App\Models\User;
use Tests\Contracts\AuthenticatableContract;

trait Authenticatable
{
    protected User $user;

    protected function authenticate(string $handler, ?string $role = null): void
    {
        /** @var AuthenticatableContract $authenticationInstance */
        $authenticationInstance = new $handler($this);

        $this->user = $authenticationInstance->authenticate($role);
    }
}
