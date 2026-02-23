<?php

namespace App\Enums;

enum GdsProvider: string
{
    case Sabre = 'sabre';
    case Flyhub = 'flyhub';

    public function label(): string
    {
        return match ($this) {
            self::Sabre => 'Sabre',
            self::Flyhub => 'Flyhub',
        };
    }
}
