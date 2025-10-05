<?php

namespace App\Services\Role\Syncer;

use App\Contracts\RoleHandlerContract;

class RoleSyncerService
{
    private array $handlers = [];

    public function __construct(array $handlers)
    {
        $this->handlers = $handlers;
    }

    public static function make(array $handlers): self
    {
        return new self($handlers);
    }

    public function sync(): void
    {
        foreach ($this->handlers as $handler) {
            self::syncRoleWithPermissions($handler);
            self::assignEligibleUsers($handler);
            self::unassignRevokableUsers($handler);
        }
    }

    public static function syncRoleWithPermissions(RoleHandlerContract $handler): void
    {
        $role        = $handler->role();
        $permissions = $handler->permissions();

        $role->permissions()->sync($permissions);
    }

    public static function assignEligibleUsers(RoleHandlerContract $handler): void
    {
        $role       = $handler->role();
        $assignable = $handler->assignable();

        foreach ($assignable as $user) {
            if (!$user->roles->contains($role->id)) {
                $user->roles()->attach($role->id);
            }
        }
    }

    public static function unassignRevokableUsers(RoleHandlerContract $handler): void
    {
        $role      = $handler->role();
        $revokable = $handler->revokable();

        foreach ($revokable as $user) {
            if ($user->roles->contains($role->id)) {
                $user->roles()->detach($role->id);
            }
        }
    }
}
