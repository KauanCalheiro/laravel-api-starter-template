<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case CREATE_USER = 'user.create';
    case READ_USER   = 'user.read';
    case UPDATE_USER = 'user.update';
    case DELETE_USER = 'user.delete';

    case ASSIGN_USER_ROLE = 'user.assign.role';
    case REVOKE_USER_ROLE = 'user.revoke.role';
    case SYNC_USER_ROLE   = 'user.sync.role';
}
