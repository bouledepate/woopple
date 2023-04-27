<?php

namespace Woopple\Models\Test;

enum QuestionType: string
{
    case OPEN = 'open';
    case CLOSED = 'closed';

    public function title(): string
    {
        return match ($this) {
            self::OPEN => 'Открытый',
            self::CLOSED => 'Закрытый'
        };
    }

    public static function values(): array
    {
        return [
            self::OPEN->value,
            self::CLOSED->value
        ];
    }
}