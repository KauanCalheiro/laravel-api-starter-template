<?php

namespace App\Http\Controllers;

use App\Helpers\Spatie\QueryBuilder\Filters\Search\SearchFilter;
use App\Http\Requests\StoreUserRequest;
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
}
