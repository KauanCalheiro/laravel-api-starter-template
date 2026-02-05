<?php

namespace App\Rules;

use App\Models\JwtToken;
use App\Providers\Storage\JwtBlacklistStorageProvider;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Tymon\JWTAuth\Blacklist;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class RefreshTokenRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $payload = JWTAuth::setToken($value)->getPayload();
        } catch (TokenInvalidException $e) {
            $fail(__('auth.refresh_token.invalid'));
            return;
        }

        $storage   = new JwtBlacklistStorageProvider(new JwtToken());
        $blackList = new Blacklist($storage);

        $isRevoked = $blackList->has($payload);

        if ($isRevoked) {
            $fail(__('auth.refresh_token.invalid'));
            return;
        }

        if ($payload->get('type') !== 'refresh') {
            $fail(__('auth.refresh_token.invalid'));
        }
    }
}
