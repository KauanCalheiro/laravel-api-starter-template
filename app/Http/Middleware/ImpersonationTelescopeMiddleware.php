<?php

namespace App\Http\Middleware;

use App\Models\User;
use Auth;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Lab404\Impersonate\Services\ImpersonateManager;
use Laravel\Telescope\Telescope;
use RuntimeException;

class ImpersonationTelescopeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $payload = Auth::payload();

            if (blank($payload)) {
                throw new RuntimeException('No JWT payload found');
            }

            $key = app(ImpersonateManager::class)->getSessionKey();

            $impersonatorId = $payload->get($key, null);

            if (blank($impersonatorId)) {
                throw new RuntimeException('No impersonation data found in token');
            }

            $impersonator = User::find($impersonatorId);

            if (!$impersonator) {
                throw new RuntimeException('Impersonator user not found');
            }

            Telescope::tag(function () use ($impersonator) {
                return [
                    "Impersonator id: {$impersonator->getKey()}",
                    "Impersonator name: {$impersonator->name}",
                    "Impersonator email: {$impersonator->email}",
                ];
            });
        } catch (Exception $ignored) {
        }

        return $next($request);
    }
}
