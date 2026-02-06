<?php

namespace App\Providers;

use App\Auth\Jwt\Contracts\ClaimsProvider;
use App\Auth\Jwt\Contracts\TokenIssuer;
use App\Auth\Jwt\Contracts\TokenRepository;
use App\Auth\Jwt\Contracts\TokenValidator;
use App\Auth\Jwt\Application\JwtTokenValidator;
use App\Auth\Jwt\Infrastructure\DefaultClaimsProvider;
use App\Auth\Jwt\Infrastructure\EloquentTokenRepository;
use App\Guards\JwtCustomGuard;
use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
    ];

    public function register()
    {
        $this->app->bind(TokenRepository::class, EloquentTokenRepository::class);
        $this->app->bind(ClaimsProvider::class, DefaultClaimsProvider::class);
        $this->app->bind(TokenValidator::class, JwtTokenValidator::class);
        $this->app->bind(TokenIssuer::class, fn () => $this->app['auth']->guard('api'));
    }

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
                $app->make(TokenRepository::class),
                $app->make(ClaimsProvider::class),
            );

            $app->refresh('request', $guard, 'setRequest');

            return $guard;
        });
    }
}
