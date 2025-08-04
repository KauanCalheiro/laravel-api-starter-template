<?php

namespace App\Services\Model;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

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

    public function syncRoles(User $requesterUser, User $targetUser, array $newRoles): self
    {
        $notAllowedRoles = array_filter($newRoles, fn ($role) => !$requesterUser->canAssignRole($role));

        if ($notAllowedRoles) {
            throw new AuthorizationException(__('authorization.role.assign.not_allowed', [
                'role' => implode(', ', $notAllowedRoles),
            ]));
        }

        $isSameUser = $requesterUser->id == $targetUser->id;

        if ($isSameUser) {
            $willEraseStrongestRole = !in_array($requesterUser->getStrongestRole(), $newRoles);

            if ($willEraseStrongestRole) {
                throw new AuthorizationException(__('authorization.role.remove.strongest.not_allowed', [
                    'role' => $requesterUser->getStrongestRole(),
                ]));
            }
        }

        $targetUserRoles = $targetUser->getRoleNames()->toArray();

        $highPrivilegeRolesToPreserve = array_filter(
            $targetUserRoles,
            fn ($role) => !$requesterUser->canAssignRole($role),
        );

        $finalRoles = array_unique(array_merge($newRoles, $highPrivilegeRolesToPreserve));

        $targetUser->syncRoles($finalRoles);

        return $this;
    }
}
