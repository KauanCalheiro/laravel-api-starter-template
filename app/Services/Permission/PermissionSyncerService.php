<?php

namespace App\Services\Permission;

use App\Enums\PermissionEnum;
use App\Models\Auth\Permission;

class PermissionSyncerService
{
    public static function sync(): void
    {
        collect(PermissionEnum::cases())->each(function ($permission) {
            Permission::firstOrCreate([
                'name' => $permission->value,
            ]);
        });
    }
}
