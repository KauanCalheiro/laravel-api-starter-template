<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case USER  = 'user';
    public static function levels(): array
    {
        return [
            self::ADMIN->value => 5,
            self::USER->value  => 1,
        ];
    }
}
