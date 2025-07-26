<?php

namespace App\Contracts;

interface LoginHandlerContract
{
    public function __construct($credentials);
    public function validate(): self;
    public function login(): array;
    public function handleLogin(): array;
}
