<?php

return [
    'id' => 'woopple',
    'basePath' => __DIR__,
    'language' => 'ru-RU',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@public' => __DIR__ . '/public'
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'wmYG0NTg6j2ZYqe2pVu8zsQfJs5ucyouNM1RdFDu'
        ]
    ],
];