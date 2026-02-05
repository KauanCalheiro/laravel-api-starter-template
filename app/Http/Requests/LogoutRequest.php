<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LogoutRequest extends FormRequest
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
            'driver' => ['in:jwt'],
        ];
    }
}
