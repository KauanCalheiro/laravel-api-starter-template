<?php

namespace App\Guards;

use App\Http\Resources\JwtTokenResource;
use App\Models\JwtToken;
use App\Models\User;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\JWT;
use Tymon\JWTAuth\JWTGuard;

class JwtCustomGuard extends JWTGuard
{
    public function __construct(JWT $jwt, UserProvider $provider, Request $request)
    {
        parent::__construct($jwt, $provider, $request);
    }

    public function login(JWTSubject $user): JwtTokenResource
    {
        parent::setUser($user);

        $this->invalidateUserTokens();

        $refresh_token = $this->refreshToken();
        $access_token  = $this->accessToken();

        return new JwtTokenResource((object) [
            'access_token'  => $access_token,
            'refresh_token' => $refresh_token,
            'expires_in'    => $this->payload()->get('exp') - time(),
        ]);
    }

    public function logout($forceForever = false): void
    {
        $this->invalidateUserTokens();

        parent::logout($forceForever);
    }

    public function refresh($forceForever = false, $resetClaims = false): JwtTokenResource
    {
        $this->invalidateUserTokens();

        return $this->login($this->user());
    }

    protected function invalidateUserTokens(): self
    {
        $user = User::find($this->user()->getAuthIdentifier());

        JwtToken::invalidadeUserTokens($user);

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

    protected function generateToken(int $ttl, string $type): string
    {
        $this->setTTL($ttl);

        $token = parent::login($this->user());

        JwtToken::create([
            'key'        => $this->getPayload()->get('jti'),
            'type'       => $type,
            'user_id'    => $this->user()->id,
            'expired_at' => now()->addMinutes($ttl)->toDateTimeString(),
        ]);

        return $token;
    }
}
