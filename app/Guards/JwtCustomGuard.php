<?php

namespace App\Guards;

use App\Auth\Jwt\Contracts\ClaimsProvider;
use App\Auth\Jwt\Contracts\TokenIssuer;
use App\Auth\Jwt\Contracts\TokenRepository;
use App\Http\Resources\JwtTokenResource;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\JWT;
use Tymon\JWTAuth\JWTGuard;
use Tymon\JWTAuth\Payload;

class JwtCustomGuard extends JWTGuard implements TokenIssuer
{
    protected array $claims = [];

    public function __construct(
        JWT $jwt,
        UserProvider $provider,
        Request $request,
        private readonly TokenRepository $tokenRepository,
        private readonly ClaimsProvider $claimsProvider,
        private ?Payload $refreshToken = null,
        private ?Payload $accessToken = null
    ) {
        parent::__construct($jwt, $provider, $request);
    }

    public function login(JWTSubject $user, bool $invalidateTokens = true): JwtTokenResource
    {
        parent::setUser($user);

        if ($invalidateTokens) {
            $this->invalidateUserTokens();
        }

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

        return $this->login($this->user(), false);
    }

    public function invalidateUserTokens(): self
    {
        $userId = $this->user()?->getAuthIdentifier();

        if ($userId) {
            $this->tokenRepository->revokeByUser($userId);
        }

        return $this;
    }

    protected function refreshToken(): string
    {
        $this->claims = [];

        return $this->claims([
            'type' => 'refresh',
        ])->generateToken(config('jwt.refresh_ttl'), 'refresh');
    }

    protected function accessToken(): string
    {
        $this->claims = [];

        return $this->claims([
            'refresh_jti' => $this->refreshToken?->get('jti'),
            'type'        => 'access',
            ...$this->claimsProvider->forUser($this->user()),
        ])->generateToken(config('jwt.ttl'), 'access');
    }

    protected function generateToken(int $ttl, string $type): string
    {
        $this->setTTL($ttl);

        parent::claims($this->claims);

        $token = parent::login($this->user());

        $payload = $this->getPayload();

        match ($type) {
            'refresh' => $this->refreshToken = $payload,
            'access'  => $this->accessToken  = $payload,
            default   => null,
        };

        $this->tokenRepository->save(
            $payload->get('jti'),
            $this->user()->getAuthIdentifier(),
            $type,
            Carbon::now()->addMinutes($ttl),
        );

        return $token;
    }

    public function claims(array $claims): self
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

    /**
     * Attempt to authenticate the user using the given credentials and return the token.
     *
     * @param  array  $credentials
     * @param  bool  $login
     * @return bool|Authenticatable
     */
    public function attempt(array $credentials = [], $login = true)
    {
        $this->lastAttempted = $user = $this->provider->retrieveByCredentials($credentials);

        if ($this->hasValidCredentials($user, $credentials)) {
            return $login ? $user : true;
        }

        return false;
    }
}
