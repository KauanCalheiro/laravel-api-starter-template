<?php

namespace App\Http\Controllers;

use App\Helpers\Spatie\QueryBuilder\Filters\Search\SearchFilter;
use App\Http\Requests\Api\V1\StoreCidadeRequest;
use App\Http\Requests\Api\V1\UpdateCidadeRequest;
use App\Models\Cidade;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CidadeController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Cidade::class, 'cidade');
    }

    public function index()
    {
        $cidades = QueryBuilder::for(Cidade::class)
            ->allowedFilters([
                'nome',
                'estado.nome',
                'estado.sigla',
                AllowedFilter::exact('ref_estado'),
                AllowedFilter::custom(
                    'search',
                    new SearchFilter([
                        'nome',
                        'estado.nome',
                        'estado.sigla',
                    ]),
                ),
            ])
            ->defaultSorts([
                'nome',
            ])
            ->allowedIncludes([
                'estado',
                'estado.pais',
            ])
            ->jsonPaginate()
            ->toArray();

        return $cidades;
    }

    public function store(StoreCidadeRequest $request)
    {
        $cidade = Cidade::create($request->validated());

        return $cidade;
    }

    public function show(Cidade $cidade)
    {
        $cidade = QueryBuilder::for(Cidade::class)
            ->allowedIncludes([
                'estado',
                'estado.pais',
            ])
            ->find($cidade->id);

        return $cidade;
    }

    public function update(UpdateCidadeRequest $request, Cidade $cidade)
    {
        $cidade->update($request->validated());

        return $cidade;
    }

    public function destroy(Cidade $cidade)
    {
        $cidade->delete();

        return $cidade;
    }
}
