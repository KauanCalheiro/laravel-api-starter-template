<?php

namespace App\Providers;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\User;
use App\Policies\CityPolicy;
use App\Policies\CountryPolicy;
use App\Policies\StatePolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class    => UserPolicy::class,
        Country::class => CountryPolicy::class,
        State::class   => StatePolicy::class,
        City::class    => CityPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
