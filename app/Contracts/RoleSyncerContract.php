<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

interface RoleSyncerContract
{
    public function roleName(): string;
    public function permissionNames(): array;
    public function eligibleUsers(): Collection;
}
