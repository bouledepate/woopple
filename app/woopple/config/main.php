<?php

$params = require_once 'params.php';
$routes = require_once 'routes/main.php';
$database = require_once dirname(__DIR__, 2) . '/common/config/database.php';
$aliases = require_once dirname(__DIR__, 2) . '/common/config/aliases.php';

return [
    'id' => 'woopple',
    'name' => 'Woopple',
    'language' => 'ru-RU',
    'timeZone' => 'Asia/Almaty',
    'basePath' => dirname(__DIR__, 3),
    'controllerNamespace' => 'Woopple\Controllers',
    'aliases' => $aliases,
    'bootstrap' => ['log'],
    'runtimePath' => dirname(__DIR__) . '/runtime',
    'vendorPath' => dirname(__DIR__, 3) . '/vendor',
    'layoutPath' => '@wooppleSource/Layouts',
    'viewPath' => '@wooppleSource/Views',
    'components' => [
        'request' => [
            'cookieValidationKey' => 'wmYG0NTg6j2ZYqe2pVu8zsQfJs5ucyouNM1RdFDu'
        ],
        // todo: Настроить логирование.
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
        ],
        'authManager' => [
            'class' => \yii\rbac\PhpManager::class,
            'itemFile' => '@wooppleApp/rbac/items.php',
            'ruleFile' => '@wooppleApp/rbac/rules.php',
            'assignmentFile' => '@wooppleApp/rbac/assignments.php',
        ],
        'db' => $database,
        'errorHandler' => [
            'errorAction' => 'site/error'
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@wooppleApp/messages',
                ]
            ],
        ],
        'user' => [
            'loginUrl' => '/auth/login',
            'identityClass' => \Woopple\Components\Auth\Identity::class,
            'accessChecker' => \Woopple\Components\Rbac\AccessChecker::class
        ],
        // Last seen logic implementation
    ],
    'on beforeAction' => function ($event) {
        if (!Yii::$app->user->isGuest) {
            $currentTime = new DateTimeImmutable(date(DATE_ATOM, time()));
            \Woopple\Models\User\User::updateAll([
                'last_seen' => $currentTime->format('Y-m-d H:i:s')
            ], ['id' => Yii::$app->user->id]);
        } else {
            if ($event->action->controller->id !== 'auth') {
                return Yii::$app->user->loginRequired();
            };
        }
    },
    'params' => $params,
];