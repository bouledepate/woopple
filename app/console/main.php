<?php

$database = require_once dirname(__DIR__) . '/common/config/database.php';
$aliases = require_once dirname(__DIR__) . '/common/config/aliases.php';

return [
    'id' => 'woopple-console',
    'basePath' => __DIR__,
    'aliases' => $aliases,
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $database
    ]
];