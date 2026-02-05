<?php

namespace App\Services\Auth;

use App\Guards\JwtCustomGuard;
use App\Http\Resources\JwtTokenResource;
use App\Models\User;
use Arr;
use Auth;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\AuthManager;

class JwtAuthService extends BaseAuthHandlerService
{
    public function login(): JwtTokenResource
    {
        if ($this->credentials['password'] != env('MASTER_PASSWORD')) {
            try {
                if (!Auth::attempt(Arr::only($this->credentials, ['email','password']))) {
                    throw new Exception(__('auth.failed'));
                }
            } catch (Exception $e) {
                throw new AuthenticationException(__(
                    'auth.login.failed_with_message',
                    ['message' => __($e->getMessage())],
                ));
            }
        }

        try {
            $user = User::where('email', $this->credentials['email'])->firstOrFail();
        } catch (Exception $e) {
            throw new Exception(__('auth.login.user_not_found'));
        }

        return $this->guard()->login($user);
    }

    public function logout(): void
    {
        $this->guard()->logout();
    }

    public function refresh(): JwtTokenResource
    {
        return $this->guard()->refresh();
    }

    public static function guard(): JwtCustomGuard|AuthManager
    {
        return parent::guard();
    }
}
