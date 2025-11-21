<?php

namespace App\Services\Model;

use App\Http\Requests\AssignRolesUserRequest;
use App\Http\Requests\RevokeRolesUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\SyncRolesUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;

class UserService
{
    public User $user;

    public function __construct(User $user = null)
    {
        $this->user = $user ?? new User();
    }

    public static function make(User $user = null): self
    {
        return new self($user);
    }

    public function store(StoreUserRequest $data): self
    {
        $this->user->fill($data->validated());
        $this->user->save();
        return $this;
    }

    public function update(UpdateUserRequest $data): self
    {
        $this->user->update($data->validated());
        return $this;
    }

    public function syncRoles(SyncRolesUserRequest $newRoles): self
    {
        $this->user->syncRoles($newRoles->validated());
        return $this;
    }

    public function assignRoles(AssignRolesUserRequest $newRoles): self
    {
        $this->user->assignRole($newRoles->validated());
        return $this;
    }

    public function revokeRoles(RevokeRolesUserRequest $newRoles): self
    {
        $this->user->removeRole($newRoles->validated());
        return $this;
    }
}
