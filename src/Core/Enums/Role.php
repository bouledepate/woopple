<?php

declare(strict_types=1);

namespace Core\Enums;

enum Role: string
{
    case DEFAULT = 'rDefaultUser';
    case ADMIN = 'rAdmin';
    case SECURITY_MANAGER = 'rSecurityManager';
    case HR = 'rHR';
    case LEAD = 'rLead';
//    case DEPARTMENT_LEAD = 'rDepartmentLead';
//    case TEAM_LEAD = 'rTeamLead';

    public static function values(): array
    {
        return [
            self::DEFAULT->value,
            self::ADMIN->value,
            self::SECURITY_MANAGER->value,
            self::HR->value,
            self::LEAD->value,
//            self::DEPARTMENT_LEAD->value,
//            self::TEAM_LEAD->value,
        ];
    }
}