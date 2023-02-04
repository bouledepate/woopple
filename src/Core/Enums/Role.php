<?php

declare(strict_types=1);

namespace Core\Enums;

enum Role: string
{
    case DEFAULT = 'rDefaultUser';
    case ADMIN = 'rAdmin';

    public static function values(): array
    {
        return [
            self::DEFAULT->value,
            self::ADMIN->value
        ];
    }
}