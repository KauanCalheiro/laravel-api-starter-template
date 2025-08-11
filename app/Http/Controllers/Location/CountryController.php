<?php

namespace App\Http\Controllers\Location;

use App\Helpers\Spatie\QueryBuilder\Filters\Search\SearchFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCountryRequest;
use App\Http\Requests\UpdateCountryRequest;
use App\Models\Location\Country;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CountryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Country::class, 'pais');
    }

    public function index()
    {
        $paises = QueryBuilder::for(Country::class)
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

    public function store(StoreCountryRequest $request)
    {
        $pais = Country::create($request->validated());

        return $pais;
    }

    public function show(Country $pais)
    {
        return $pais;
    }

    public function update(UpdateCountryRequest $request, Country $pais)
    {
        $pais->update($request->validated());

        return $pais;
    }

    public function destroy(Country $pais)
    {
        $pais->delete();

        return $pais;
    }
}
