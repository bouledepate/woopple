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
            self::NEW->value => '<span class="badge badge-info">Новый запрос</span>',
            self::IN_PROGRESS->value => '<span class="badge badge-primary">На рассмотрении</span>',
            self::WAITING->value => '<span class="badge badge-warning">В ожидании</span>',
            self::DONE->value => '<span class="badge badge-success">Завершён</span>',
            self::REFUSED->value => '<span class="badge badge-danger">Отклонён</span>'
        ];
    }
}