<?php

namespace App\Guards;

use App\Auth\Jwt\Contracts\ClaimsProvider;
use App\Auth\Jwt\Contracts\TokenIssuer;
use App\Auth\Jwt\Contracts\TokenRepository;
use App\Http\Resources\JwtTokenResource;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\JWT;
use Tymon\JWTAuth\JWTGuard;

class JwtCustomGuard extends JWTGuard implements TokenIssuer
{
    protected array $claims = [];

    public function __construct(
        JWT $jwt,
        UserProvider $provider,
        Request $request,
        private readonly TokenRepository $tokenRepository,
        private readonly ClaimsProvider $claimsProvider,
    ) {
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
        $userId = $this->user()?->getAuthIdentifier();

        if ($userId) {
            $this->tokenRepository->revokeByUser($userId);
        }

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

        $mergedClaims = [
            ...$this->claimsProvider->forUser($this->user()),
            ...$this->claims ?? [],
            'type' => $type,
        ];

        parent::claims($mergedClaims);

        $token = parent::login($this->user());

        $payload = $this->getPayload();

        $this->tokenRepository->save(
            $payload->get('jti'),
            $this->user()->getAuthIdentifier(),
            $type,
            Carbon::now()->addMinutes($ttl),
        );

        return $token;
    }

    public function claims(array $claims)
    {
        $this->claims = [
            ...$this->claims ?? [],
            ...$claims,
        ];

        return $this;
    }

    public function issueTokens(JWTSubject $user, array $claims = []): JwtTokenResource
    {
        return $this->claims($claims)->login($user);
    }

    public function refreshTokens(string $refreshToken): JwtTokenResource
    {
        $this->setToken($refreshToken);

        return $this->refresh();
    }

    public function revokeUserTokens(int $userId): void
    {
        $this->tokenRepository->revokeByUser($userId);
    }
}
