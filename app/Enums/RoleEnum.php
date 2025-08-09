<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case USER  = 'user';

    public static function ordered(): array
    {
        return [
            self::ADMIN,
            self::USER,
        ];
    }
}
