<?php

namespace Core\Enum;

enum Environment: string
{
    case DEVELOPMENT = "dev";
    case PRODUCTION = "prod";

    public static function current(): Environment
    {
        return match ($_ENV['ENVIRONMENT']) {
            "dev" => self::DEVELOPMENT,
            "prod" => self::PRODUCTION
        };
    }
}