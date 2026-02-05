<?php

namespace App\Providers;

use App\Guards\JwtCustomGuard;
use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
        $this->extendAuthGuard();
    }

    protected function extendAuthGuard()
    {
        $this->app['auth']->extend('jwt-custom', function ($app, $name, array $config) {
            $guard = new JwtCustomGuard(
                $app['tymon.jwt'],
                $app['auth']->createUserProvider($config['provider']),
                $app['request'],
            );

            $app->refresh('request', $guard, 'setRequest');

            return $guard;
        });
    }
}
