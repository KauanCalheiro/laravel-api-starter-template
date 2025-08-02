<?php

namespace App\Http\Controllers;

use App\Helpers\Spatie\QueryBuilder\Filters\Search\SearchFilter;
use App\Http\Resources\UserResource;
use App\Models\User;
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

    public function show(User $user)
    {
        $user = QueryBuilder::for(User::class)
            ->allowedIncludes([
                'roles',
            ])
            ->findOrFail($user->id);

        return new UserResource($user);
    }
}
