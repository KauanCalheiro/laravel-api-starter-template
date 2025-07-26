<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;

class GuestRedirectHandler
{
    public function __invoke(Request $request): ?string
    {
        if ($this->expectsJson($request)) {
            return null;
        }

        return $this->redirectToLogin();
    }

    private function expectsJson(Request $request): bool
    {
        return $request->expectsJson() || $request->is('api/*');
    }

    public function redirectTo(string $name): ?string
    {
        return route($name);
    }

    public function redirectToLogin(): ?string
    {
        return $this->redirectTo('login');
    }
}
