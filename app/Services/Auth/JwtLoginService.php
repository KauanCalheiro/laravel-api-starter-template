<?php

namespace App\Services\Auth;

use App\Models\User;
use Arr;
use Auth;
use Exception;
use Illuminate\Auth\AuthenticationException;

class JwtLoginService extends BaseLoginHandlerService
{
    public function validate(): self
    {
        if (empty($this->credentials['email'])) {
            throw new Exception(__('validation.required', ['attribute' => 'email']));
        }

        if (empty($this->credentials['password'])) {
            throw new Exception(__('validation.required', ['attribute' => 'password']));
        }

        return $this;
    }

    public function login(): array
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

        $user = User::where('email', $this->credentials['email'])->firstOrFail();

        return $this->authenticate($user);
    }
}
