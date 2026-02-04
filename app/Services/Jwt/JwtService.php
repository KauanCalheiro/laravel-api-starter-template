<?php

namespace App\Services\Jwt;

use App\Http\Resources\JwtTokenResource;
use App\Models\JwtToken;
use App\Models\User;
use Illuminate\Auth\AuthManager;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;

class JwtService
{
    private User $user;
    private JWTGuard|AuthManager $auth;

    public function __construct(User $user)
    {
        auth()->hasUser() ?: auth()->setUser($user);

        $this->user = $user;
        $this->auth = auth();
    }

    public static function make(User $user): self
    {
        return new self($user);
    }

    public function login(): JwtTokenResource
    {
        $refresh_token = $this->refreshToken();
        $access_token  = $this->accessToken();

        return new JwtTokenResource((object) [
            'access_token'  => $access_token,
            'refresh_token' => $refresh_token,
        ]);
    }

    public function refresh(): JwtTokenResource
    {
        $this->auth->invalidate(true);

        return $this->login();
    }

    public function claims(array $claims): self
    {
        $this->auth->claims($claims);
        return $this;
    }

    protected function refreshToken(): string
    {
        return $this->generateToken(config('jwt.refresh_ttl'), 'refresh');
    }

    protected function accessToken(): string
    {
        return $this->generateToken(config('jwt.ttl'), 'access');
    }

    private function generateToken(int $ttl, string $type): string
    {
        $token = $this->auth->setTTL($ttl)->login($this->user);

        JwtToken::create([
            'key'        => $this->auth->getPayload()->get('jti'),
            'value'      => $token,
            'type'       => $type,
            'user_id'    => $this->user->id,
            'expired_at' => now()->addMinutes($ttl)->toDateTimeString(),
        ]);

        return $token;
    }
}
