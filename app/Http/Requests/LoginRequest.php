<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function prepareForValidation(): void
    {
        $this->merge([
            'driver' => $this->input('driver', 'sanctum'),
        ]);
    }

    public function rules(): array
    {
        $driver = $this->input('driver', 'sanctum');

        return match(true) {
            $driver === 'sanctum' => $this->sanctumRules(),
            $driver === 'google'  => $this->googleRules(),
        };
    }

    private function googleRules()
    {
        return [
            'token'  => ['required', 'string'],
            'driver' => ['in:sanctum,google'],
        ];
    }

    private function sanctumRules()
    {
        return [
            'email'       => ['required', 'string', 'email'],
            'password'    => ['required', 'string'],
            'remember_me' => ['boolean'],
            'driver'      => ['in:sanctum,google'],
        ];
    }
}
