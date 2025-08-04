<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\Location\State;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StatePolicy
{
    protected string $modelName;

    public function __construct()
    {
        $this->modelName = __('model.name.state');
    }

    public function viewAny(User $user)
    {
        if ($user->can(PermissionEnum::READ_STATE)) {
            return Response::allow();
        }

        return Response::deny(__(
            'policy.responses.deny.view',
            ['model' => $this->modelName],
        ));
    }

    public function view(User $user, State $estado)
    {
        if ($user->can(PermissionEnum::READ_STATE)) {
            return Response::allow();
        }

        return Response::deny(__(
            'policy.responses.deny.view',
            ['model' => $this->modelName],
        ));
    }

    public function create(User $user)
    {
        if ($user->can(PermissionEnum::CREATE_STATE)) {
            return Response::allow();
        }

        return Response::deny(__(
            'policy.responses.deny.create',
            ['model' => $this->modelName],
        ));
    }

    public function update(User $user, State $estado)
    {
        if ($user->can(PermissionEnum::UPDATE_STATE)) {
            return Response::allow();
        }

        return Response::deny(__(
            'policy.responses.deny.update',
            ['model' => $this->modelName],
        ));
    }

    public function delete(User $user, State $estado)
    {
        if ($user->can(PermissionEnum::DELETE_STATE)) {
            return Response::allow();
        }

        return Response::deny(__(
            'policy.responses.deny.delete',
            ['model' => $this->modelName],
        ));
    }
}
