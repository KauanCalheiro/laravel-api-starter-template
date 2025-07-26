<?php

namespace App\Helpers;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use InvalidArgumentException;

class RoleHelper
{
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function resolve(?string $role): string
    {
        $roleIsFilled = !empty($role);
        $userHasRole  = $roleIsFilled && $this->user->hasRole($role);

        if (!$roleIsFilled || !$userHasRole) {
            $role = $this->user->getStrongestRole();
        }

        $roleIsRecognized = RoleEnum::tryFrom($role);

        if (!$roleIsRecognized) {
            throw new InvalidArgumentException(__('validation.enum', ['attribute' => $role]));
        }

        return $role;
    }

    public function queryMatch(Builder $query, string $role): Builder
    {
        return match ($role) {
            RoleEnum::ADMIN->value => $query->admin($this->user),
            RoleEnum::USER->value  => $query->aluno($this->user),
            default                => throw new InvalidArgumentException(__('validation.enum', ['attribute' => $role])),
        };
    }
}
