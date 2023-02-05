<?php

namespace Woopple\Components\Enums;

enum RestorePasswordStatus: int
{
    case NEW = 1;
    case IN_PROGRESS = 2;
    case WAITING = 3;
    case DONE = 4;
    case REFUSED = 5;

    public static function values(): array
    {
        return [
            self::NEW->value,
            self::IN_PROGRESS->value,
            self::WAITING->value,
            self::DONE->value,
            self::REFUSED->value
        ];
    }

    public static function titles(): array
    {
        return [
            self::NEW->value => 'Новый запрос',
            self::IN_PROGRESS->value => 'На рассмотрении',
            self::WAITING->value => 'В ожидании',
            self::DONE->value => 'Завершён',
            self::REFUSED->value => 'Отклонён'
        ];
    }
}