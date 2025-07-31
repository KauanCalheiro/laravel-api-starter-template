<?php

namespace App\Models\Auth;

use App\Traits\LogsAll;
use Spatie\Permission\Models\Role as BaseRole;

class Role extends BaseRole
{
    use LogsAll;

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
