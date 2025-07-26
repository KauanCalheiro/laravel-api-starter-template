<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\Pais;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PaisPolicy
{
    protected string $modelName;

    public function __construct()
    {
        $this->modelName = __('model.name.country');
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        if ($user->can(PermissionEnum::READ_PAIS)) {
            return Response::allow();
        }

        return Response::deny(__(
            'policy.responses.deny.view',
            ['model' => $this->modelName],
        ));
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Pais $pais)
    {
        if ($user->can(PermissionEnum::READ_PAIS)) {
            return Response::allow();
        }

        return Response::deny(__(
            'policy.responses.deny.view',
            ['model' => $this->modelName],
        ));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        if ($user->can(PermissionEnum::CREATE_PAIS)) {
            return Response::allow();
        }

        return Response::deny(__(
            'policy.responses.deny.create',
            ['model' => $this->modelName],
        ));
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Pais $pais)
    {
        if ($user->can(PermissionEnum::UPDATE_PAIS)) {
            return Response::allow();
        }

        return Response::deny(__(
            'policy.responses.deny.update',
            ['model' => $this->modelName],
        ));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Pais $pais)
    {
        if ($user->can(PermissionEnum::DELETE_PAIS)) {
            return Response::allow();
        }

        return Response::deny(__(
            'policy.responses.deny.delete',
            ['model' => $this->modelName],
        ));
    }
}
