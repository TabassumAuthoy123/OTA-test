<?php

namespace App\Enums;

enum BookingStatus: int
{
    case Pending = 0;
    case Confirmed = 1;
    case Cancelled = 2;
    case Ticketed = 3;
    case Expired = 4;

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Confirmed => 'Confirmed',
            self::Cancelled => 'Cancelled',
            self::Ticketed => 'Ticketed',
            self::Expired => 'Expired',
        };
    }
}
