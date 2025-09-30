<?php

namespace App\Http\Controllers\Location;

use App\Helpers\Spatie\QueryBuilder\Filters\Search\SearchFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCountryRequest;
use App\Http\Requests\UpdateCountryRequest;
use App\Http\Resources\CountryResource;
use App\Models\Location\Country;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CountryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Country::class, 'country');
    }

    public function index()
    {
        $countries = QueryBuilder::for(Country::class)
            ->allowedFilters([
                'name',
                'code',
                AllowedFilter::custom('search', new SearchFilter(['name', 'code'])),
            ])
            ->defaultSort('name')
            ->jsonPaginate();

        return CountryResource::collection($countries);
    }

    public function store(StoreCountryRequest $request)
    {
        $country = Country::create($request->validated());

        return new CountryResource($country);
    }

    public function show(Country $country)
    {
        return new CountryResource($country);
    }

    public function update(UpdateCountryRequest $request, Country $country)
    {
        $country->update($request->validated());

        return new CountryResource($country);
    }

    public function destroy(Country $country)
    {
        return $this->empty(fn () => $country->delete());
    }
}
