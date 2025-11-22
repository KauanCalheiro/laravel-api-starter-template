<?php

use App\Exceptions\ApiExceptionHandler;
use App\Exceptions\TelescopeUnauthenticatedHandler;
use App\Http\Middleware\ForceJsonResponse;
use App\Http\Middleware\GuestRedirectHandler;
use App\Http\Middleware\ImpersonationTelescopeMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = require __DIR__ . '/../config/app.php';

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: $app['health_route'],
        apiPrefix: 'api',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(GuestRedirectHandler::class);
        $middleware->api([
            ForceJsonResponse::class,
            ImpersonationTelescopeMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $throwable) {
            return match (true) {
                request()->is('api/*')                    => (new ApiExceptionHandler(app()))->render(request(), $throwable),
                request()->is('telescope', 'telescope/*') => (new TelescopeUnauthenticatedHandler())->handler(request(), $throwable),
                default                                   => null,
            };
        });
    })->create();
