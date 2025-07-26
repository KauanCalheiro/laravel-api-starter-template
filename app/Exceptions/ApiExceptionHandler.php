<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

class ApiExceptionHandler extends Handler
{
    /**
     * Render the exception as an HTTP response.
     */
    public function render($request, Throwable $throwable)
    {
        if ($request->is('api/*')) {
            return match (true) {
                $throwable instanceof AuthenticationException => response()->json([
                    'error' => __($throwable->getMessage()),
                ], Response::HTTP_UNAUTHORIZED),

                $throwable instanceof ValidationException => response()->json([
                    'error'  => __($throwable->getMessage()),
                    'errors' => $throwable->errors(),
                ], Response::HTTP_UNPROCESSABLE_ENTITY),

                $throwable instanceof NotFoundHttpException => response()->json([
                    'error' => __('route.error.not_found'),
                ], Response::HTTP_NOT_FOUND),

                $throwable instanceof HttpException => response()->json([
                    'error' => __($throwable->getMessage()),
                ], $throwable->getStatusCode()),

                $throwable instanceof RouteNotFoundException => response()->json([
                    'error' => __('route.error.not_found'),
                ], Response::HTTP_NOT_FOUND),

                default => response()->json([
                    'error' => __($throwable->getMessage()),
                ], Response::HTTP_INTERNAL_SERVER_ERROR),
            };
        }

        return parent::render($request, $throwable);
    }
}
