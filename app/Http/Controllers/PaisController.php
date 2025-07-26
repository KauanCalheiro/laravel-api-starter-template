<?php

namespace App\Http\Controllers;

use App\Helpers\Spatie\QueryBuilder\Filters\Search\SearchFilter;
use App\Http\Requests\Api\V1\StorePaisRequest;
use App\Http\Requests\Api\V1\UpdatePaisRequest;
use App\Models\Pais;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PaisController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Pais::class, 'pais');
    }

    public function index()
    {
        $paises = QueryBuilder::for(Pais::class)
            ->allowedFilters([
                'nome',
                'sigla',
                AllowedFilter::custom('search', new SearchFilter(['nome', 'sigla'])),
            ])
            ->allowedSorts(['nome', 'sigla'])
            ->jsonPaginate()
            ->toArray();

        return $paises;
    }

    public function store(StorePaisRequest $request)
    {
        $pais = Pais::create($request->validated());

        return $pais;
    }

    public function show(Pais $pais)
    {
        return $pais;
    }

    public function update(UpdatePaisRequest $request, Pais $pais)
    {
        $pais->update($request->validated());

        return $pais;
    }

    public function destroy(Pais $pais)
    {
        $pais->delete();

        return $pais;
    }
}
