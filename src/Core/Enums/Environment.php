<?php

namespace Core\Enums;

enum Environment: string
{
    case DEVELOPMENT = "dev";
    case PRODUCTION = "prod";
    case TEST = 'test';

    public static function current(): Environment
    {
        return match ($_ENV['ENVIRONMENT']) {
            "dev" => self::DEVELOPMENT,
            "prod" => self::PRODUCTION,
            'test' => self::TEST
        };
    }
}