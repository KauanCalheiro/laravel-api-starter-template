<?php

namespace App\Services\Role\Handler;

use App\Contracts\RoleHandlerContract;
use App\Enums\RoleEnum;
use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class AdminRoleHandler implements RoleHandlerContract
{
    public const ADMIN_USERS_EMAIL = [
        'admin@example.com',
    ];

    public function role(): Role
    {
        return Role::where('name', RoleEnum::ADMIN->value)->firstOrFail();
    }

    public function permissions(): Collection
    {
        return Permission::all();
    }

    public function assignable(): Collection
    {
        return User::whereIn('email', self::ADMIN_USERS_EMAIL)
            ->where(function (Builder $query) {
                $query->whereDoesntHave('roles')
                    ->orWhereDoesntHave('roles', function (Builder $subquery) {
                        $subquery->where('name', RoleEnum::ADMIN->value);
                    });
            })
            ->get();
    }

    public function revokable(): Collection
    {
        return User::whereNotIn('email', self::ADMIN_USERS_EMAIL)
            ->whereHas('roles', function (Builder $query) {
                $query->where('name', RoleEnum::ADMIN->value);
            })
            ->get();
    }
}
