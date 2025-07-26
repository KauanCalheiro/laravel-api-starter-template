<?php

namespace App\Services\Permission;

use App\Enums\PermissionEnum;
use App\Models\Permission;

class PermissionSyncer
{
    /**
     * @var string[]|null
     */
    protected $permissions;

    public function __construct(?array $permissions = null)
    {
        $this->permissions = $permissions ?: $this->getDefaultPermissions();
    }

    public static function make(?array $permissions = null): self
    {
        return new self($permissions);
    }

    protected function getDefaultPermissions(): array
    {
        return collect(PermissionEnum::cases())
            ->map(fn ($permission) => $permission->value)
            ->toArray();
    }

    public function sync(): void
    {
        collect($this->permissions)->each(function ($name) {
            Permission::firstOrCreate([
                'name' => $name,
            ]);
        });
    }
}
