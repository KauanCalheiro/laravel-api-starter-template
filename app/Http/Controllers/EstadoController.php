<?php

namespace App\Http\Controllers;

use App\Helpers\Spatie\QueryBuilder\Filters\Search\SearchFilter;
use App\Http\Requests\Api\V1\StoreEstadoRequest;
use App\Http\Requests\Api\V1\UpdateEstadoRequest;
use App\Models\Estado;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EstadoController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Estado::class, 'estado');
    }

    public function index()
    {
        $estados = QueryBuilder::for(Estado::class)
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

    public function store(StoreEstadoRequest $request)
    {
        $estado = Estado::create($request->validated());

        return $estado;
    }

    public function show(Estado $estado)
    {
        $estado = QueryBuilder::for(Estado::class)
            ->allowedIncludes(['pais'])
            ->find($estado->id);

        return $estado;
    }

    public function update(UpdateEstadoRequest $request, Estado $estado)
    {
        $estado->update($request->validated());

        return $estado;
    }

    public function destroy(Estado $estado)
    {
        $estado->delete();

        return $estado;
    }
}
