<?php

namespace App\Traits;

use App\Enums\RoleEnum;
use Exception;
use Spatie\Permission\Traits\HasRoles as SpatieHasRoles;

trait HasRoles
{
    use SpatieHasRoles;

    public function getStrongestRole(): string
    {
        $roles = $this->getRoleNames() ?? collect();

        $priority = [
            RoleEnum::ADMIN->value,
            RoleEnum::USER->value,
        ];

        foreach ($priority as $role) {
            if ($roles->contains($role)) {
                return $role;
            }
        }

        throw new Exception(__('authorization.role.not_found'));
    }
}
