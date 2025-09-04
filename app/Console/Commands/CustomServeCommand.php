<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Console\ServeCommand;

class CustomServeCommand extends ServeCommand
{
    protected function handleProcessOutput()
    {
        $routes = $this->resolveUsefulRoutes();

        $this->components->info("See documentation on [{$routes['docs']}]");
        $this->components->info("See health endpoint at [{$routes['health']}]");
        $this->components->info("See application logs at [{$routes['telescope']}]");

        return parent::handleProcessOutput();
    }

    private function resolveUsefulRoutes(): array
    {
        return [
            'docs'      => $this->buildRouteUrl('docs.scalar.index'),
            'health'    => $this->buildRouteUrl(fn () => config('app.health_route')),
            'telescope' => $this->buildRouteUrl('telescope'),
        ];
    }

    private function buildRouteUrl($routeNameOrResolver): string
    {
        $baseUrl = "http://{$this->host()}:{$this->port()}";

        $path = is_callable($routeNameOrResolver)
            ? call_user_func($routeNameOrResolver)
            : route(name: $routeNameOrResolver, absolute: false);

        return "{$baseUrl}{$path}";
    }
}
