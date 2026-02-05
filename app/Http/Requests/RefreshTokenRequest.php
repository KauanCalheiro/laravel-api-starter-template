<?php

namespace App\Http\Requests;

use App\Rules\RefreshTokenRule;
use Illuminate\Foundation\Http\FormRequest;

class RefreshTokenRequest extends FormRequest
{
    public function prepareForValidation(): void
    {
        $this->merge([
            'driver' => $this->input('driver', 'jwt'),
        ]);
    }

    public function rules(): array
    {
        $driver = $this->input('driver', 'jwt');

        return match($driver) {
            'jwt' => $this->jwtRules(),
        };
    }

    private function jwtRules()
    {
        return [
            'refresh_token' => [
                'required',
                'string',
                new RefreshTokenRule(),
            ],
            'driver' => ['in:jwt'],
        ];
    }
}
