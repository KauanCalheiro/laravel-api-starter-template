<?php

namespace App\Http\Controllers\Location;

use App\Helpers\Spatie\QueryBuilder\Filters\Search\SearchFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStateRequest;
use App\Http\Requests\UpdateStateRequest;
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
        $estados = QueryBuilder::for(State::class)
            ->allowedFilters([
                'nome',
                'sigla',
                AllowedFilter::custom(
                    'search',
                    new SearchFilter([
                        'nome',
                        'sigla',
                        'pais.nome',
                        'pais.sigla',
                    ]),
                ),
            ])
            ->allowedSorts(['nome', 'sigla'])
            ->allowedIncludes(['pais'])
            ->jsonPaginate()
            ->toArray();

        return $estados;
    }

    public function store(StoreStateRequest $request)
    {
        $estado = State::create($request->validated());

        return $estado;
    }

    public function show(State $estado)
    {
        $estado = QueryBuilder::for(State::class)
            ->allowedIncludes(['pais'])
            ->find($estado->id);

        return $estado;
    }

    public function update(UpdateStateRequest $request, State $estado)
    {
        $estado->update($request->validated());

        return $estado;
    }

    public function destroy(State $estado)
    {
        $estado->delete();

        return $estado;
    }
}
