<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\Location\Country;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CountryPolicy
{
    protected string $modelName;

    public function __construct()
    {
        $this->modelName = __('model.name.country');
    }

    public function viewAny(User $user)
    {
        if ($user->can(PermissionEnum::READ_COUNTRY)) {
            return Response::allow();
        }

        return Response::deny(__(
            'policy.responses.deny.view',
            ['model' => $this->modelName],
        ));
    }

    public function view(User $user, Country $pais)
    {
        if ($user->can(PermissionEnum::READ_COUNTRY)) {
            return Response::allow();
        }

        return Response::deny(__(
            'policy.responses.deny.view',
            ['model' => $this->modelName],
        ));
    }

    public function create(User $user)
    {
        if ($user->can(PermissionEnum::CREATE_COUNTRY)) {
            return Response::allow();
        }

        return Response::deny(__(
            'policy.responses.deny.create',
            ['model' => $this->modelName],
        ));
    }

    public function update(User $user, Country $pais)
    {
        if ($user->can(PermissionEnum::UPDATE_COUNTRY)) {
            return Response::allow();
        }

        return Response::deny(__(
            'policy.responses.deny.update',
            ['model' => $this->modelName],
        ));
    }

    public function delete(User $user, Country $pais)
    {
        if ($user->can(PermissionEnum::DELETE_COUNTRY)) {
            return Response::allow();
        }

        return Response::deny(__(
            'policy.responses.deny.delete',
            ['model' => $this->modelName],
        ));
    }
}
