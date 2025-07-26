<?php

namespace App\Providers;

use App\Models\Cidade;
use App\Models\Estado;
use App\Models\Pais;
use App\Models\User;
use App\Policies\CidadePolicy;
use App\Policies\EstadoPolicy;
use App\Policies\PaisPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class   => UserPolicy::class,
        Pais::class   => PaisPolicy::class,
        Estado::class => EstadoPolicy::class,
        Cidade::class => CidadePolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
