<?php

namespace App\Services\Permission;

use App\Contracts\RoleSyncerContract;
use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Support\Collection;

class AdminRoleSyncer implements RoleSyncerContract
{
    public function roleName(): string
    {
        return RoleEnum::ADMIN->value;
    }

    public function permissionNames(): array
    {
        return ['*'];
    }

    public function eligibleUsers(): Collection
    {
        $roleName = $this->roleName();
        return User::whereIn('id', [733787, 622302])
            ->where(function ($query) use ($roleName) {
                $query->whereHas('roles', function ($q) use ($roleName) {
                    $q->whereNot('name', $roleName);
                })->orWhereDoesntHave('roles');
            })
            ->get();
    }
}
