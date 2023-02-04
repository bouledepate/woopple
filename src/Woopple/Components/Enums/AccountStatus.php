<?php

namespace Woopple\Components\Enums;

enum AccountStatus: int
{
    case ACTIVE = 1;
    case BLOCKED = 2;

    public static function values(): array
    {
        return [
            self::ACTIVE->value,
            self::BLOCKED->value
        ];
    }
}