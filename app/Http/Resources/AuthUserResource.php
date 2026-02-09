<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->resource->loadMissing([
            'roles'             => fn ($query) => $query->select('roles.id', 'roles.name'),
            'roles.permissions' => fn ($query) => $query->select('permissions.id', 'permissions.name'),
        ]);

        $permissions = $this->roles
            ->flatMap->permissions
            ->pluck('name')
            ->unique()
            ->values();

        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'email'       => $this->email,
            'active_role' => $this->active_role,
            'roles'       => $this->roles->pluck('name'),
            'permissions' => $permissions,
        ];
    }
}
