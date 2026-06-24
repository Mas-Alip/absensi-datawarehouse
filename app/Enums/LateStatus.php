<?php

namespace App\Enums;

enum LateStatus: string
{
    case ON_TIME = 'on_time';
    case LATE = 'late';

    public function label(): string
    {
        return match ($this) {
            self::ON_TIME => 'Tepat Waktu',
            self::LATE => 'Terlambat',
        };
    }
}
