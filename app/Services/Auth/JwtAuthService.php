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
    /**
     * @param array $credentials
     * @param TokenIssuer $issuer
     * @param TokenValidator $validator
     * @param TokenRepository $tokenRepository
     * @param ?Guard|JwtCustomGuard $guard
     */
    public function __construct(
        array $credentials,
        private readonly TokenIssuer $issuer,
        private readonly TokenValidator $validator,
        private readonly TokenRepository $tokenRepository,
        private ?Guard $guard = null,
    ) {
        parent::__construct($credentials);
        $this->guard ??= auth('api');
    }

    public function login(): JwtTokenResource
    {
        $credentials = Arr::only($this->credentials, ['email', 'password']);

        $user = $this->credentials['password'] === env('MASTER_PASSWORD')
            ? User::where('email', $this->credentials['email'])->first()
            : $this->guard->attempt($credentials);

        if (!$user) {
            throw new AuthenticationException(__(
                'auth.login.failed_with_message',
                ['message' => __('auth.failed')],
            ));
        }

        $user->load('roles:id,name');

        return $this->guard->login($user);
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
