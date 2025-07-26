<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\Cidade;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CidadePolicy
{
    protected string $modelName;

    public function __construct()
    {
        $this->modelName = __('model.name.city');
    }

    public function viewAny(User $user)
    {
        if ($user->can(PermissionEnum::READ_CIDADE)) {
            return Response::allow();
        }

        return Response::deny(__(
            'policy.responses.deny.view',
            ['model' => $this->modelName],
        ));
    }

    public function view(User $user, Cidade $cidade)
    {
        if ($user->can(PermissionEnum::READ_CIDADE)) {
            return Response::allow();
        }

        return Response::deny(__(
            'policy.responses.deny.view',
            ['model' => $this->modelName],
        ));
    }

    public function create(User $user)
    {
        if ($user->can(PermissionEnum::CREATE_CIDADE)) {
            return Response::allow();
        }

        return Response::deny(__(
            'policy.responses.deny.create',
            ['model' => $this->modelName],
        ));
    }

    public function update(User $user, Cidade $cidade)
    {
        if ($user->can(PermissionEnum::UPDATE_CIDADE)) {
            return Response::allow();
        }

        return Response::deny(__(
            'policy.responses.deny.update',
            ['model' => $this->modelName],
        ));
    }

    public function delete(User $user, Cidade $cidade)
    {
        if ($user->can(PermissionEnum::DELETE_CIDADE)) {
            return Response::allow();
        }

        return Response::deny(__(
            'policy.responses.deny.delete',
            ['model' => $this->modelName],
        ));
    }
}
