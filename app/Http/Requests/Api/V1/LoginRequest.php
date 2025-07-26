<?php

namespace App\Http\Requests\Api\V1;

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

        return $driver == 'google'
            ? $this->googleRules()
            : $this->sanctumRules();
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
            'login'       => ['required', 'string'],
            'password'    => ['required', 'string'],
            'remember_me' => ['boolean'],
            'driver'      => ['in:sanctum,google'],
        ];
    }
}
