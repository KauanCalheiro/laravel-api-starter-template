<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\User;

class UserPolicy
{
    public function viewAny(User $authenticated): bool
    {
        return $authenticated->can(PermissionEnum::READ_USER);
    }

    public function view(User $authenticated, User $user): bool
    {
        return $authenticated->can(PermissionEnum::READ_USER);
    }

    public function create(User $authenticated): bool
    {
        return $authenticated->can(PermissionEnum::CREATE_USER);
    }

    public function update(User $authenticated, User $user): bool
    {
        $selfUpdate = $authenticated->id === $user->id;
        $canUpdate  = $authenticated->can(PermissionEnum::UPDATE_USER);

        return $selfUpdate || $canUpdate;
    }

    public function delete(User $authenticated, User $user): bool
    {
        $selfDelete = $authenticated->id === $user->id;
        $canDelete  = $authenticated->can(PermissionEnum::DELETE_USER);

        return !$selfDelete || $canDelete;
    }

    public function assignRoles(User $authenticated, User $user): bool
    {
        return $authenticated->can(PermissionEnum::ASSIGN_USER_ROLE);
    }

    public function revokeRoles(User $authenticated, User $user): bool
    {
        return $authenticated->can(PermissionEnum::REVOKE_USER_ROLE);
    }

    public function syncRoles(User $authenticated, User $user): bool
    {
        return $authenticated->can(PermissionEnum::SYNC_USER_ROLE);
    }
}
