<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case READ_USER   = 'read_user';
    case ASSIGN_ROLE = 'assign_role';
    case REVOKE_ROLE = 'revoke_role';

    case CREATE_PAIS = 'create_pais';
    case READ_PAIS   = 'read_pais';
    case UPDATE_PAIS = 'update_pais';
    case DELETE_PAIS = 'delete_pais';

    case CREATE_ESTADO = 'create_estado';
    case READ_ESTADO   = 'read_estado';
    case UPDATE_ESTADO = 'update_estado';
    case DELETE_ESTADO = 'delete_estado';

    case CREATE_CIDADE = 'create_cidade';
    case READ_CIDADE   = 'read_cidade';
    case UPDATE_CIDADE = 'update_cidade';
    case DELETE_CIDADE = 'delete_cidade';
}
