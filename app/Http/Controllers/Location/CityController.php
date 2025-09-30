<?php

namespace App\Http\Controllers\Location;

use App\Helpers\Spatie\QueryBuilder\Filters\Search\SearchFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\UpdateCityRequest;
use App\Http\Resources\CityResource;
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
        $cities = QueryBuilder::for(City::class)
            ->allowedFilters([
                'name',
                'state.name',
                'state.code',
                AllowedFilter::exact('state_id'),
                AllowedFilter::custom(
                    'search',
                    new SearchFilter([
                        'name',
                        'state.name',
                        'state.code',
                    ]),
                ),
            ])
            ->defaultSort('name')
            ->allowedIncludes([
                'state',
                'state.country',
            ])
            ->jsonPaginate();

        return CityResource::collection($cities);
    }

    public function store(StoreCityRequest $request)
    {
        $city = City::create($request->validated());

        return new CityResource($city);
    }

    public function show(City $city)
    {
        $city = QueryBuilder::for(City::class)
            ->allowedIncludes([
                'state',
                'state.country',
            ])
            ->find($city->id);

        return new CityResource($city);
    }

    public function update(UpdateCityRequest $request, City $city)
    {
        $city->update($request->validated());

        return new CityResource($city);
    }

    public function destroy(City $city)
    {
        return $this->empty(fn () => $city->delete());
    }
}
