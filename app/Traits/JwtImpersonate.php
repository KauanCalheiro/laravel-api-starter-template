<?php

namespace App\Traits;

use App\Models\User;
use Auth;
use Lab404\Impersonate\Models\Impersonate;
use Lab404\Impersonate\Services\ImpersonateManager;
use RuntimeException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Payload;

trait JwtImpersonate
{
    use Impersonate;

    public function isImpersonated(): bool
    {
        $user = Auth::user();

        if (blank($user)) {
            return false;
        }

        $payload = $this->getJwtPayload();

        return filled($payload->get($this->getImpersonateKey()));
    }

    public function impersonate(User $to): string
    {
        if (!$this->canImpersonate()) {
            throw new RuntimeException(__('impersonate.cannot.impersonate'));
        }

        if (!$to->canBeImpersonated()) {
            throw new RuntimeException(__('impersonate.cannot.be_impersonated'));
        }

        if ($this->id === $to->id) {
            throw new RuntimeException(__('impersonate.cannot.impersonate_yourself'));
        }

        if ($this->isImpersonated()) {
            throw new RuntimeException(__('impersonate.already_impersonating'));
        }

        $key = $this->getImpersonateKey();

        $token = JWTAuth::claims([
            $key => $this->id,
        ])->fromUser($to);

        return $token;
    }

    public function leaveImpersonation(): string
    {
        $user = Auth::user();

        if (blank($user)) {
            throw new RuntimeException(__('impersonate.auth_user_not_found'));
        }

        $payload = $this->getJwtPayload();

        $impersonatorId = $payload->get($this->getImpersonateKey());

        if (!$impersonatorId) {
            throw new RuntimeException(__('impersonate.not_impersonating_anyone'));
        }

        $impersonator = User::findOrFail($impersonatorId);

        return JWTAuth::fromUser($impersonator);
    }

    private function getImpersonateKey(): string
    {
        return app(ImpersonateManager::class)->getSessionKey();
    }

    public function getImpersonatorId(): ?int
    {
        $payload = $this->getJwtPayload();

        return $payload->get($this->getImpersonateKey());
    }

    public function getImpersonator(): ?User
    {
        $impersonatorId = $this->getImpersonatorId();

        if (blank($impersonatorId)) {
            return null;
        }

        return User::find($impersonatorId);
    }

    private function getJwtPayload(): Payload
    {
        /** @var \Tymon\JWTAuth\Payload $payload */
        $payload = Auth::payload();

        return $payload;
    }
}
