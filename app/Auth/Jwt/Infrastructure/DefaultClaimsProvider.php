<?php

namespace App\Auth\Jwt\Infrastructure;

use App\Auth\Jwt\Contracts\ClaimsProvider;
use Illuminate\Contracts\Auth\Authenticatable;

class DefaultClaimsProvider implements ClaimsProvider
{
    public function forUser(Authenticatable $user): array
    {
        return [];
    }
}
