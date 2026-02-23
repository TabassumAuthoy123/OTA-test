<?php

namespace App\Enums;

enum UserType: int
{
    case SuperAdmin = 0;
    case Admin = 1;
    case B2B = 2;
    case B2C = 3;

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super Admin',
            self::Admin => 'Admin',
            self::B2B => 'B2B Agent',
            self::B2C => 'B2C Customer',
        };
    }
}
