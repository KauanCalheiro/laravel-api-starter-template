<?php

namespace App\Auth\Jwt\Contracts;

use Illuminate\Http\Resources\Json\JsonResource;

interface AuthHandler
{
    public function login(): JsonResource;

    public function logout(): void;

    public function refresh(): JsonResource;
}
