<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceJsonResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->expectsJson()) {
            $request->headers->set('Accept', 'application/json');
        }

        $response = $next($request);

        if ($response instanceof JsonResponse) {
            $data = $response->getData(true);
            $response->setData($data);
        }

        return $response;
    }
}
