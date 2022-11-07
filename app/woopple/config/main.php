<?php

$params = require_once 'params.php';
$routes = require_once 'routes/main.php';

return [
    'id' => 'woopple',
    'name' => 'Woopple',
    'language' => 'ru-RU',
    'timeZone' => 'Asia/Almaty',
    'basePath' => dirname(__DIR__, 3),
    'runtimePath' => dirname(__DIR__) . '/runtime',
    'vendorPath' => dirname(__DIR__, 3) . '/vendor',
    'layoutPath' => '@woopple/Layouts',
    'viewPath' => '@woopple/Views',
    'controllerNamespace' => 'Woopple\Controllers',
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@public' => dirname(__DIR__, 3) . '/public',
        '@woopple' => dirname(__DIR__, 3) . '/src/Woopple',
        '@wooppleConfig' => dirname(__DIR__)
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
];