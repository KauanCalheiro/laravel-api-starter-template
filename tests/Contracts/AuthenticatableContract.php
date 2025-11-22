<?php

namespace Tests\Contracts;

use App\Models\User;
use Tests\TestCase;

interface AuthenticatableContract
{
    public function __construct(TestCase $test);
    public function authenticate(?string $role = null): User;
}
