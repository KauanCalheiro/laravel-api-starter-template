<?php

namespace App\Services\Model;

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

    public static function store(array $data): self
    {
        $service       = new self();
        $service->user = User::create($data);
        return $service;
    }

    public function update(array $data): self
    {
        $this->user->update($data);
        return $this;
    }

    public function syncRoles(array $newRoles): self
    {
        $this->user->syncRoles($newRoles);
        return $this;
    }

    public function assignRoles(array $newRoles): self
    {
        $this->user->assignRole($newRoles);
        return $this;
    }

    public function revokeRoles(array $newRoles): self
    {
        $this->user->removeRole($newRoles);
        return $this;
    }
}
