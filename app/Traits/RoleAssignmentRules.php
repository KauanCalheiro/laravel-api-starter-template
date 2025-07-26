<?php

namespace App\Traits;

use App\Enums\RoleEnum;

trait RoleAssignmentRules
{
    public function canAssignRole(string $role): bool
    {
        $levels = RoleEnum::levels();

        $userStrongest = $this->getStrongestRole();

        $roleExists          = array_key_exists($role, $levels);
        $userStrongestExists = array_key_exists($userStrongest, $levels);

        if (!$roleExists || !$userStrongestExists) {
            return false;
        }

        $userRoleIsHigher = $levels[$role] <= $levels[$userStrongest];

        if (!$userRoleIsHigher) {
            return false;
        }

        return true;
    }

    public function canAssignRoles(array $roles): bool
    {
        foreach ($roles as $role) {
            if (!$this->canAssignRole($role)) {
                return false;
            }
        }

        return true;
    }

    public function getAssignableRoles(): array
    {
        return array_values(
            array_filter(
                array_map(fn ($role) => $role->value, RoleEnum::cases()),
                fn ($role) => $this->canAssignRole($role),
            ),
        );
    }
}
