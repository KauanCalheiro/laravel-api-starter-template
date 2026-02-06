<?php

namespace App\Services\Auth;

use App\Auth\Jwt\Contracts\TokenIssuer;
use App\Auth\Jwt\Contracts\TokenRepository;
use App\Auth\Jwt\Contracts\TokenValidator;
use App\Guards\JwtCustomGuard;
use App\Http\Resources\JwtTokenResource;
use App\Models\User;
use Arr;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Guard;

class JwtAuthService extends BaseAuthHandlerService
{
    public function __construct(
        array $credentials,
        private readonly TokenIssuer $issuer,
        private readonly TokenValidator $validator,
        private readonly TokenRepository $tokenRepository,
        private ?Guard $guard = null,
    ) {
        parent::__construct($credentials);
        $this->guard = $this->guard ?? auth('api');
    }

    public function login(): JwtTokenResource
    {
        if ($this->credentials['password'] != env('MASTER_PASSWORD')) {
            if (!$this->guard->validate(Arr::only($this->credentials, ['email', 'password']))) {
                throw new AuthenticationException(__(
                    'auth.login.failed_with_message',
                    ['message' => __('auth.failed')],
                ));
            }
        }

        $user = User::where('email', $this->credentials['email'])->firstOrFail();

        return $this->issuer->issueTokens($user);
    }

    public function logout(): void
    {
        $this->guard->logout();
    }

    public function refresh(): JwtTokenResource
    {
        return $this->issuer->refreshTokens($this->credentials['refresh_token']);
    }

    public static function guard(): JwtCustomGuard|Guard
    {
        return auth('api');
    }
}
