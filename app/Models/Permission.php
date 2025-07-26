<?php

namespace App\Models;

use App\Traits\LogsAll;
use Spatie\Permission\Models\Permission as BasePermission;

class Permission extends BasePermission
{
    use LogsAll;

    public $hidden = [
        'created_at',
        'updated_at',
    ];
}
