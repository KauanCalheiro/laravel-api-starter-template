<?php

namespace Tests\Helpers\Auth;

use App\Enums\RoleEnum;
use App\Models\User;
use Tests\Contracts\AuthenticatableContract;
use Tests\TestCase;

abstract class BaseAuthenticatable implements AuthenticatableContract
{
    protected User $user;
    protected TestCase $test;

    public function __construct(TestCase $test)
    {
        $this->test = $test;
    }

    protected function user(?string $role = null): void
    {
        $this->user = User::role($this->resolveRole($role))->first();
    }

    private function resolveRole(?string $role): string
    {
        return $role ?? RoleEnum::ADMIN->value;
    }
}
