<?php

namespace App\Http\Controllers;

use App\Helpers\AuthorizeMethod;
use App\Helpers\Spatie\QueryBuilder\Filters\Search\SearchFilter;
use App\Http\Requests\AssignRolesUserRequest;
use App\Http\Requests\RevokeRolesUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\SyncRolesUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Model\UserService;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
        $this->authorizeMethods([
            new AuthorizeMethod('assignRoles', 'user'),
            new AuthorizeMethod('revokeRoles', 'user'),
            new AuthorizeMethod('syncRoles', 'user'),
        ]);
    }

    public function index()
    {
        $users = QueryBuilder::for(User::class)
            ->allowedFilters([
                AllowedFilter::exact('id'),
                'name',
                'email',
                AllowedFilter::custom(
                    'search',
                    new SearchFilter([
                        'id',
                        'name',
                    ]),
                ),
            ])
            ->allowedIncludes([
                'roles',
            ])
            ->allowedSorts([
                'id',
                'name',
                'email',
            ])
            ->jsonPaginate();

        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request)
    {
        return new UserResource(
            UserService::store($request->validated())->user,
        );
    }

    public function show(User $user)
    {
        $user = QueryBuilder::for(User::class)
            ->allowedIncludes([
                'roles',
            ])
            ->findOrFail($user->id);

        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        return new UserResource(
            UserService::make($user)->update($request->validated())->user,
        );
    }

    public function destroy(User $user)
    {
        return $this->empty(fn () => $user->delete());
    }

    public function assignRoles(AssignRolesUserRequest $request, User $user)
    {
        $service = UserService::make($user)->assignRoles($request->validated());
        $service->user->load('roles');

        return new UserResource(
            $service->user,
        );
    }

    public function revokeRoles(RevokeRolesUserRequest $request, User $user)
    {
        $service = UserService::make($user)->revokeRoles($request->validated());
        $service->user->load('roles');

        return new UserResource(
            $service->user,
        );
    }

    public function syncRoles(SyncRolesUserRequest $request, User $user)
    {
        $service = UserService::make($user)->syncRoles($request->validated());
        $service->user->load('roles');

        return new UserResource(
            $service->user,
        );
    }
}
