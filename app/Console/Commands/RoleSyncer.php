<?php

namespace App\Console\Commands;

use App\Enums\RoleEnum;
use App\Services\Permission\PermissionSyncerService;
use App\Services\Role\Handler\AdminRoleHandler;
use App\Services\Role\Handler\UserRoleHandler;
use App\Services\Role\Syncer\RoleSyncerService;
use Illuminate\Console\Command;

class RoleSyncer extends Command
{
    protected $signature   = 'role:sync {roles?* : The role names to sync (admin, user)}';
    protected $description = 'Sync roles and permissions';

    protected AdminRoleHandler $adminHandler;
    protected UserRoleHandler $userHandler;

    public function __construct(AdminRoleHandler $adminHandler, UserRoleHandler $userHandler)
    {
        parent::__construct();
        $this->adminHandler = $adminHandler;
        $this->userHandler  = $userHandler;
    }

    public function handle(): int
    {
        $roles = $this->argument('roles');

        $handlers = $this->resolveHandlers($roles);

        if (!$handlers) {
            return self::FAILURE;
        }

        PermissionSyncerService::sync();

        $syncer = new RoleSyncerService($handlers);
        $syncer->sync();

        $this->info('Sync completed successfully!');
        return self::SUCCESS;
    }

    protected function resolveHandlers(?array $roles): array|null
    {
        $availableHandlers = [
            RoleEnum::ADMIN->value => $this->adminHandler,
            RoleEnum::USER->value  => $this->userHandler,
        ];

        if (empty($roles)) {
            return $this->resolveAllHandlers($availableHandlers);
        }

        return $this->resolveBatchHandlers($roles, $availableHandlers);
    }

    protected function resolveBatchHandlers(array $roles, array $availableHandlers): array|null
    {
        $resolvedHandlers = [];

        foreach ($roles as $roleName) {
            if (!$this->roleExists($roleName, $availableHandlers)) {
                $this->warn("Role '{$roleName}' not found.");
                continue;
            }

            $this->info("Syncing role: {$roleName}");
            $resolvedHandlers[] = $availableHandlers[$roleName];
        }

        if (empty($resolvedHandlers)) {
            $validRoles = implode(', ', array_keys($availableHandlers));

            $this->error("No valid roles to sync. Valid roles are: [{$validRoles}]");

            return null;
        }

        return $resolvedHandlers;
    }

    protected function resolveAllHandlers(array $availableHandlers): array
    {
        $this->info('Syncing all roles...');
        return array_values($availableHandlers);
    }

    protected function roleExists(string $roleName, array $availableHandlers): bool
    {
        return isset($availableHandlers[$roleName]);
    }
}
