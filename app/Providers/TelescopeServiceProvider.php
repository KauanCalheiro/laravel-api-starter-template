<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->hideSensitiveRequestDetails();
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     */
    protected function hideSensitiveRequestDetails(): void
    {
        Telescope::hideRequestParameters(config('telescope.hidden.request.parameters', []));
        Telescope::hideResponseParameters(config('telescope.hidden.response.parameters', []));
        Telescope::hideRequestHeaders(config('telescope.hidden.request.headers', []));
    }

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     */
    protected function gate(): void
    {
        Gate::define('viewTelescope', function ($user) {
            return $user->hasRole('admin');
        });
    }
}
