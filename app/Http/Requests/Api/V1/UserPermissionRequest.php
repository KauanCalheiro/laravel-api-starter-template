<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UserPermissionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'permission' => ['required', 'string', 'max:255', 'exists:permissions,name'],
        ];
    }
}
