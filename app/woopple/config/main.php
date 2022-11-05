<?php

$params = require_once 'params.php';
$routes = require_once 'routes/main.php';

return [
    'id' => 'woopple',
    'name' => 'Woopple',
    'basePath' => dirname(__DIR__, 3),
    'language' => 'ru-RU',
    'timeZone' => 'Asia/Almaty',
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@public' => dirname(__DIR__, 3) . '/public',
        '@woopple' => dirname(__DIR__, 3) . '/src/Woopple',
        '@console' => dirname(__DIR__, 3) . '/src/Console',
        '@core' => dirname(__DIR__, 3) . '/src/Core'
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'wmYG0NTg6j2ZYqe2pVu8zsQfJs5ucyouNM1RdFDu'
        ],
        'log' => [
            'traceLevel' => 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info', 'error', 'warning'],
                    'logVars' => []
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => $routes,
        ]
    ],
    'params' => $params,
    'layoutPath' => '@woopple/Layouts',
    'viewPath' => '@woopple/Views',
    'vendorPath' => dirname(__DIR__, 3) . '/vendor',
    'runtimePath' => dirname(__DIR__) . '/runtime',
    'controllerNamespace' => 'Woopple\Controllers'
];