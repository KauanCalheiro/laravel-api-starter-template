<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class BlockRefreshTokenMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->route()?->getName() === 'auth.refresh') {
            return $next($request);
        }

        $token = $request->bearerToken();

        if (!$token) {
            return $next($request);
        }

        try {
            $payload = JWTAuth::setToken($token)->getPayload();
        } catch (JWTException) {
            return $next($request);
        }

        if ($payload->get('type') === 'refresh') {
            return response([
                'message' => __('auth.refresh_token.cannot_use'),
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
