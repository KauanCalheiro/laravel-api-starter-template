<?php

namespace App\Enums;

enum DriverEnum: string
{
    case POSTGRES = 'pgsql';
    case SQLITE   = 'sqlite';

    public static function match(DriverEnum $driver, string $value): bool
    {
        return $value === $driver->value;
    }
}
