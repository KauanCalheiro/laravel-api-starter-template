<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'jwt'    => $this->jwtRules(),
            'google' => $this->googleRules(),
        };
    }

    private function googleRules()
    {
        return [
            'token'  => ['required', 'string'],
            'driver' => ['in:jwt,google'],
        ];
    }

    private function jwtRules()
    {
        return [
            'email'       => ['required', 'string', 'email'],
            'password'    => ['required', 'string'],
            'remember_me' => ['boolean'],
            'driver'      => ['in:jwt,google'],
        ];
    }
}
