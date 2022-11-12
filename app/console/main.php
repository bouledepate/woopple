<?php

$database = require 'database.php';

return [
    'id' => 'woopple-console',
    'basePath' => __DIR__,
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@public' => dirname(__DIR__, 3) . '/public',
        '@wooppleSource' => dirname(__DIR__, 3) . '/src/Woopple',
        '@wooppleApp' => dirname(__DIR__)
    ],
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