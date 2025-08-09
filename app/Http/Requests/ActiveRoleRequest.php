<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActiveRoleRequest extends FormRequest
{
    public function rules(): array
    {
        $userRoles = $this->user()->roles->pluck('name')->join(',');

        return [
            'role' => ['required', 'string', 'exists:roles,name', "in:{$userRoles}"],
        ];
    }
}
