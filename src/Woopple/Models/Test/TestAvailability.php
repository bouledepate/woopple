<?php

namespace Woopple\Models\Test;

enum TestAvailability: string
{
    case COMMON = 'common';
    case USER_ONLY = 'user_only';
    case TEAM_ONLY = 'team_only';
    case DEP_ONLY = 'dep_only';

    public function title(): string
    {
        return match ($this) {
            self::COMMON => 'Общий тест',
            self::USER_ONLY => 'Только для пользователя',
            self::TEAM_ONLY => 'Только для команды',
            self::DEP_ONLY => 'Только для отдела'
        };
    }

    public static function titles(): array
    {
        return [
            self::COMMON->value => 'Общий тест',
            self::USER_ONLY->value => 'Только для пользователя',
            self::TEAM_ONLY->value => 'Только для команды',
            self::DEP_ONLY->value => 'Только для отдела'
        ];
    }

    public static function values(): array
    {
        return [
            self::COMMON->value,
            self::USER_ONLY->value,
            self::TEAM_ONLY->value,
            self::DEP_ONLY->value
        ];
    }
}