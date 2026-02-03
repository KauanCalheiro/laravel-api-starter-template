<?php

namespace App\Contracts;

use App\Http\Resources\JwtTokenResource;

interface LoginHandlerContract
{
    public function __construct($credentials);
    public function validate(): self;
    public function login(): JwtTokenResource;
    public function handleLogin(): JwtTokenResource;
}
