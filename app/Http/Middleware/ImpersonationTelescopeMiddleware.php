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
                throw new RuntimeException(__('impersonate.jwt_payload_missing'));
            }

            $key = app(ImpersonateManager::class)->getSessionKey();

            $impersonatorId = $payload->get($key, null);

            if (blank($impersonatorId)) {
                throw new RuntimeException(__('impersonate.impersonation_data_missing'));
            }

            $impersonator = User::find($impersonatorId);

            if (!$impersonator) {
                throw new RuntimeException(__('impersonate.impersonator_not_found'));
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
