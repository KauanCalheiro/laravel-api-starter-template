<?php

namespace App\Auth\Jwt\Infrastructure;

use App\Auth\Jwt\Contracts\ClaimsProvider;
use App\Models\User;
use App\Services\Auth\ActiveRoleResolver;
use Illuminate\Contracts\Auth\Authenticatable;

class DefaultClaimsProvider implements ClaimsProvider
{
    public function __construct(private readonly ActiveRoleResolver $resolver)
    {
    }

    public function forUser(Authenticatable $user): array
    {
        if (!$user instanceof User) {
            return [];
        }

        return [
            'active_role' => $this->resolver->resolve($user),
        ];
    }
}
