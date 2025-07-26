<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(PermissionEnum::READ_USER);
    }

    public function view(User $user): bool
    {
        return $user->can(PermissionEnum::READ_USER);
    }
}
