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

    public static function titles(): array
    {
        return [
            self::ACTIVE->value => '<span class="badge badge-info badge-pill">Активный</span>',
            self::BLOCKED->value => '<span class="badge badge-danger badge-pill">Заблокирован</span>'
        ];
    }
}