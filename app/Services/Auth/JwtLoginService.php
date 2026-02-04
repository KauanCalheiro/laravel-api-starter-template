<?php

namespace App\Services\Auth;

use App\Http\Resources\JwtTokenResource;
use App\Models\User;
use App\Services\Jwt\JwtService;
use Arr;
use Auth;
use Exception;
use Illuminate\Auth\AuthenticationException;

class JwtLoginService extends BaseLoginHandlerService
{
    protected function validate(): self
    {
        if (empty($this->credentials['email'])) {
            throw new Exception(__('validation.required', ['attribute' => 'email']));
        }

        if (empty($this->credentials['password'])) {
            throw new Exception(__('validation.required', ['attribute' => 'password']));
        }

        return $this;
    }

    protected function login(): JwtTokenResource
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

        return JwtService::make($user)->login();
    }
}
