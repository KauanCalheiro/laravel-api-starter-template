<?php

namespace App\Services\Auth;

use App\Guards\JwtCustomGuard;
use App\Http\Resources\JwtTokenResource;
use App\Models\User;
use Arr;
use Exception;
use Illuminate\Contracts\Auth\Guard;

class JwtAuthService extends BaseAuthHandlerService
{
    public function login(): JwtTokenResource
    {
        if ($this->credentials['password'] != env('MASTER_PASSWORD')) {
            if (!$this->guard()->validate(Arr::only($this->credentials, ['email','password']))) {
                throw new Exception(__('auth.failed'));
            }
        }

        $user = User::where('email', $this->credentials['email'])->firstOrFail();

        return $this->guard()->login($user);
    }

    public function logout(): void
    {
        $this->guard()->logout();
    }

    public function refresh(): JwtTokenResource
    {
        $this->guard()->setToken($this->credentials['refresh_token']);
        return $this->guard()->refresh();
    }

    public static function guard(): JwtCustomGuard|Guard
    {
        return auth('api');
    }
}
