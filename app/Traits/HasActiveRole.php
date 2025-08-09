<?php

namespace App\Traits;

use App\Enums\RoleEnum;
use App\Models\Auth\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use RuntimeException;

trait HasActiveRole
{
    public function activeRoleRelation(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_active_role');
    }

    public function getActiveRoleAttribute(): ?string
    {
        return $this->activeRoleRelation()
            ->value('name')
            ?? $this->getStrongestRole()->name;
    }

    public function setActiveRole(?Role $role = null): void
    {
        $this->activeRoleRelation()->sync(
            ($role ?? $this->getStrongestRole())->getKey(),
        );
    }

    public function getStrongestRole(): Role
    {
        $userRoles = $this->roles->pluck('name');

        throw_if($userRoles->isEmpty(), RuntimeException::class, 'User has no roles assigned.');

        $strongestRoleName = collect(RoleEnum::ordered())
            ->first(fn ($role) => $userRoles->contains($role->value))
            ?->value;

        return Role::whereName($strongestRoleName)->firstOrFail();
    }
}
