<?php

declare(strict_types=1);

namespace Core\Enums;

enum Role: string
{
    case DEFAULT = 'rDefaultUser';
    case ADMIN = 'rAdmin';
    case SECURITY_MANAGER = 'rSecurityManager';
    case HR = 'rHR';

    public static function values(): array
    {
        return [
            self::DEFAULT->value,
            self::ADMIN->value,
            self::SECURITY_MANAGER->value,
            self::HR->value
        ];
    }
}