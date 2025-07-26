<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UserRoleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'roles'   => ['array'],
            'roles.*' => ['required', 'string', 'max:255', 'exists:roles,name'],
        ];
    }
}
