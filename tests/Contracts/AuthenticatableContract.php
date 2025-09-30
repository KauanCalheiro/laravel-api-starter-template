<?php

namespace Tests\Contracts;

use Tests\TestCase;

interface AuthenticatableContract
{
    public function __construct(TestCase $test);
    public function autenticate(?string $role = null): void;
}
