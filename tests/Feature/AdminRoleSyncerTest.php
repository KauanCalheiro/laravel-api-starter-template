<?php

namespace Tests\Feature;

use App\Enums\RoleEnum;
use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use App\Models\User;
use App\Services\Role\Handler\AdminRoleHandler;
use App\Services\Role\Syncer\RoleSyncerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRoleSyncerTest extends TestCase
{
    use RefreshDatabase;

    private AdminRoleHandler $handler;
    private RoleSyncerService $syncer;
    private Role $adminRole;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->adminRole = Role::firstOrFail(
            ['name' => RoleEnum::ADMIN->value],
        );

        $this->handler = new AdminRoleHandler();
        $this->syncer  = new RoleSyncerService([$this->handler]);
    }

    private function getUser(string $email, string $name = 'User'): User
    {
        return User::firstOrCreate(
            ['email' => $email],
            [
                'name'     => $name,
                'password' => bcrypt('password'),
            ],
        );
    }

    public function test_it_assigns_only_allowed_users(): void
    {
        $allowedUser = $this->getUser($this->handler::ADMIN_USERS_EMAIL[0], 'Admin');
        $allowedUser->roles()->sync([]);

        $blockedUser = $this->getUser('blocked@example.com', 'Blocked');
        $blockedUser->roles()->sync($this->handler->role()->id);

        $assignable = $this->handler->assignable();

        $this->assertTrue($assignable->contains($allowedUser));
        $this->assertFalse($assignable->contains($blockedUser));
    }

    public function test_it_revokes_only_unallowed_users(): void
    {
        $allowedUser = $this->getUser($this->handler::ADMIN_USERS_EMAIL[0], 'Admin');
        $allowedUser->roles()->sync([]);

        $blockedUser = $this->getUser('blocked@example.com', 'Blocked');
        $blockedUser->roles()->sync($this->handler->role()->id);

        $revokable = $this->handler->revokable();

        $this->assertTrue($revokable->contains($blockedUser));
        $this->assertFalse($revokable->contains($allowedUser));
    }

    public function test_it_syncs_permissions_correctly(): void
    {
        $this->syncer->sync();

        $this->assertEquals(Permission::count(), Role::where($this->adminRole->id)->first()->permissions()->count());
    }

    public function test_it_is_idempotent_when_sync_is_called_multiple_times(): void
    {
        for ($i = 0; $i < 2; $i++) {
            $this->syncer->sync();
        }

        $this->assertEquals(Permission::count(), Role::where($this->adminRole->id)->first()->permissions()->count());
    }
}
