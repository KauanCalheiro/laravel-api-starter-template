<?php

namespace App\Http\Controllers\Location;

use App\Helpers\Spatie\QueryBuilder\Filters\Search\SearchFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\UpdateCityRequest;
use App\Models\Location\City;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CityController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(City::class, 'city');
    }

    public function index()
    {
        $cidades = QueryBuilder::for(City::class)
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

    public function store(StoreCityRequest $request)
    {
        $cidade = City::create($request->validated());

        return $cidade;
    }

    public function show(City $cidade)
    {
        $cidade = QueryBuilder::for(City::class)
            ->allowedIncludes([
                'estado',
                'estado.pais',
            ])
            ->find($cidade->id);

        return $cidade;
    }

    public function update(UpdateCityRequest $request, City $cidade)
    {
        $cidade->update($request->validated());

        return $cidade;
    }

    public function destroy(City $cidade)
    {
        $cidade->delete();

        return $cidade;
    }
}
