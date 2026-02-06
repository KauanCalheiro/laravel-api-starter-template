<?php

namespace App\Rules;

use App\Auth\Jwt\Contracts\TokenValidator;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class RefreshTokenRule implements ValidationRule
{
    public function __construct(
        private readonly ?TokenValidator $validator = null,
    ) {
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            ($this->validator ?? app(TokenValidator::class))->assertRefreshToken($value);
        } catch (TokenInvalidException $e) {
            $fail(__('auth.refresh_token.invalid'));
            return;
        }
    }
}
