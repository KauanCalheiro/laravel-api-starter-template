<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\Location\City;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CityPolicy
{
    protected string $modelName;

    public function __construct()
    {
        $this->modelName = __('model.name.city');
    }

    public function viewAny(User $user)
    {
        if ($user->can(PermissionEnum::READ_CITY)) {
            return Response::allow();
        }

        return Response::deny(__(
            'policy.responses.deny.view',
            ['model' => $this->modelName],
        ));
    }

    public function view(User $user, City $cidade)
    {
        if ($user->can(PermissionEnum::READ_CITY)) {
            return Response::allow();
        }

        return Response::deny(__(
            'policy.responses.deny.view',
            ['model' => $this->modelName],
        ));
    }

    public function create(User $user)
    {
        if ($user->can(PermissionEnum::CREATE_CITY)) {
            return Response::allow();
        }

        return Response::deny(__(
            'policy.responses.deny.create',
            ['model' => $this->modelName],
        ));
    }

    public function update(User $user, City $cidade)
    {
        if ($user->can(PermissionEnum::UPDATE_CITY)) {
            return Response::allow();
        }

        return Response::deny(__(
            'policy.responses.deny.update',
            ['model' => $this->modelName],
        ));
    }

    public function delete(User $user, City $cidade)
    {
        if ($user->can(PermissionEnum::DELETE_CITY)) {
            return Response::allow();
        }

        return Response::deny(__(
            'policy.responses.deny.delete',
            ['model' => $this->modelName],
        ));
    }
}
