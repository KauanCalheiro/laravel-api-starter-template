<?php

namespace App\Services\Auth;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Support\Collection;
use RuntimeException;
use Tymon\JWTAuth\Exceptions\JWTException;

class ActiveRoleResolver
{
    public function resolve(User $user, ?string $preferredRole = null): string
    {
        $roleNames = $this->getRoleNames($user);

        if ($preferredRole) {
            if (!$roleNames->contains($preferredRole)) {
                throw new RuntimeException('User does not have the requested role.');
            }

            return $preferredRole;
        }

        $tokenRole = $this->fromToken();

        if ($tokenRole && $roleNames->contains($tokenRole)) {
            return $tokenRole;
        }

        return $this->strongestRole($roleNames);
    }

    public function fromToken(): ?string
    {
        $guard = auth('api');

        if (!method_exists($guard, 'getToken')) {
            return null;
        }

        $token = $guard->getToken();

        if (!$token) {
            return null;
        }

        try {
            return $guard->payload()->get('active_role');
        } catch (JWTException) {
            return null;
        }
    }

    private function getRoleNames(User $user): Collection
    {
        $roles = $user->relationLoaded('roles')
            ? $user->roles
            : $user->roles()->select('name')->get();

        if ($roles->isEmpty()) {
            throw new RuntimeException('User has no roles assigned.');
        }

        return $roles->pluck('name');
    }

    private function strongestRole(Collection $roleNames): string
    {
        return collect(RoleEnum::ordered())
            ->map(fn ($role) => $role->value)
            ->first(fn (string $role) => $roleNames->contains($role))
            ?? $roleNames->first();
    }
}
