<?php

namespace Woopple\Models\Test;

enum TestState: string
{
    case PROCESS = 'in_process';
    case PASSED = 'passed';
    case EXPIRED = 'expired';
    case CANCELED = 'canceled';

    public function title(): string
    {
        return match ($this) {
            self::PROCESS => 'В работе',
            self::PASSED => 'Пройден',
            self::EXPIRED => 'Просрочен',
            self::CANCELED => 'Отменён',
        };
    }

    public static function values(): array
    {
        return [
            self::PROCESS->value,
            self::PASSED->value,
            self::EXPIRED->value,
            self::CANCELED->value,
        ];
    }
}