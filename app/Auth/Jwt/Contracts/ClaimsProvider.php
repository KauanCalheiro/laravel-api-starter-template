<?php

namespace App\Auth\Jwt\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;

interface ClaimsProvider
{
    public function forUser(Authenticatable $user): array;
}
