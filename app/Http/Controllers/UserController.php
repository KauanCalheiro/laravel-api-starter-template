<?php

namespace App\Http\Controllers;

use App\Helpers\Spatie\QueryBuilder\Filters\Search\SearchFilter;
use App\Http\Requests\UserRoleRequest;
use App\Models\User;
use App\Services\UserService;
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
            ->jsonPaginate()
            ->toArray();

        foreach ($users['data'] as &$user) {
            if (isset($user['roles'])) {
                $user['roles'] = collect($user['roles'])->pluck('name')->toArray();
            }
        }

        return $users;
    }

    public function show(User $user)
    {
        $user = QueryBuilder::for(User::class)
            ->allowedIncludes(['roles'])
            ->findOrFail($user->id);

        $user = $user->toArray();

        if (isset($user['roles'])) {
            $user['roles'] = collect($user['roles'])->pluck('name')->toArray();
        }

        return $user;
    }

    public function syncRoles(UserRoleRequest $request, User $user)
    {
        UserService::make()->syncRoles(
            auth()->user(),
            $user,
            $request->roles,
        );

        return $user->load(['roles']);
    }
}
