<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\Estado;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EstadoPolicy
{
    protected string $modelName;

    public function __construct()
    {
        $this->modelName = __('model.name.state');
    }

    public function viewAny(User $user)
    {
        if ($user->can(PermissionEnum::READ_ESTADO)) {
            return Response::allow();
        }

        return Response::deny(__(
            'policy.responses.deny.view',
            ['model' => $this->modelName],
        ));
    }

    public function view(User $user, Estado $estado)
    {
        if ($user->can(PermissionEnum::READ_ESTADO)) {
            return Response::allow();
        }

        return Response::deny(__(
            'policy.responses.deny.view',
            ['model' => $this->modelName],
        ));
    }

    public function create(User $user)
    {
        if ($user->can(PermissionEnum::CREATE_ESTADO)) {
            return Response::allow();
        }

        return Response::deny(__(
            'policy.responses.deny.create',
            ['model' => $this->modelName],
        ));
    }

    public function update(User $user, Estado $estado)
    {
        if ($user->can(PermissionEnum::UPDATE_ESTADO)) {
            return Response::allow();
        }

        return Response::deny(__(
            'policy.responses.deny.update',
            ['model' => $this->modelName],
        ));
    }

    public function delete(User $user, Estado $estado)
    {
        if ($user->can(PermissionEnum::DELETE_ESTADO)) {
            return Response::allow();
        }

        return Response::deny(__(
            'policy.responses.deny.delete',
            ['model' => $this->modelName],
        ));
    }
}
