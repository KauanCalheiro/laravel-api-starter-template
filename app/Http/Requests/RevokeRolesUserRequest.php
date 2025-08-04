<?php

namespace App\Http\Requests;

use App\Models\Auth\Role;
use Illuminate\Foundation\Http\FormRequest;

class RevokeRolesUserRequest extends FormRequest
{
    protected Role $role;

    public function __construct()
    {
        $this->role = new Role();
    }

    public function rules(): array
    {
        return [
            'roles'   => ['required', 'array'],
            'roles.*' => [
                'required',
                'string',
                "exists:{$this->role->getTable()},name",
            ],
        ];
    }
}
