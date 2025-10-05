<?php

namespace App\Services\Role\Handler;

use App\Contracts\RoleHandlerContract;
use App\Enums\RoleEnum;
use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRoleHandler implements RoleHandlerContract
{
    public function role(): Role
    {
        return Role::where('name', RoleEnum::USER->value)->firstOrFail();
    }

    public function permissions(): Collection
    {
        return Permission::whereIn('name', [
        ])->get();
    }

    public function assignable(): Collection
    {
        return User::all();
    }

    public function revokable(): Collection
    {
        return new Collection([]);
    }
}
