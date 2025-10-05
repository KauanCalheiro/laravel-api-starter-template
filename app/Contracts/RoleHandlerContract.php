<?php

namespace App\Contracts;

use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use Illuminate\Database\Eloquent\Collection;

interface RoleHandlerContract
{
    /**
     * @return \App\Models\Auth\Role
     */
    public function role(): Role;

    /**
     * @return \Illuminate\Database\Eloquent\Collection<Permission>
     */
    public function permissions(): Collection;

    /**
     * @return \Illuminate\Database\Eloquent\Collection<\App\Models\User>
     */
    public function assignable(): Collection;

    /**
     * @return \Illuminate\Database\Eloquent\Collection<\App\Models\User>
     */
    public function revokable(): Collection;
}
