<?php

namespace App\Http\Controllers\Location;

use App\Helpers\Spatie\QueryBuilder\Filters\Search\SearchFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStateRequest;
use App\Http\Requests\UpdateStateRequest;
use App\Http\Resources\StateResource;
use App\Models\Location\State;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class StateController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(State::class, 'state');
    }

    public function index()
    {
        $states = QueryBuilder::for(State::class)
            ->allowedFilters([
                'name',
                'code',
                AllowedFilter::custom(
                    'search',
                    new SearchFilter([
                        'name',
                        'code',
                        'country.name',
                        'country.code',
                    ]),
                ),
            ])
            ->defaultSort('name')
            ->allowedIncludes(['country'])
            ->jsonPaginate();

        return StateResource::collection($states);
    }

    public function store(StoreStateRequest $request)
    {
        $state = State::create($request->validated());

        return new StateResource($state);
    }

    public function show(State $state)
    {
        $state = QueryBuilder::for(State::class)
            ->allowedIncludes(['pais'])
            ->find($state->id);

        return new StateResource($state);
    }

    public function update(UpdateStateRequest $request, State $state)
    {
        $state->update($request->validated());

        return new StateResource($state);
    }

    public function destroy(State $state)
    {
        return $this->empty(fn () => $state->delete());
    }
}
