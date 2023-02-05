<?php

return [
    'oAccessAdminPanel' => [
        'type' => 2,
        'description' => 'Доступ к панели администрирования'
    ],
    'oAccessUserManagement' => [
        'type' => 2,
        'description' => 'Доступ к просмотру раздела пользователей в панели администратора'
    ],
    'oCreateUser' => [
        'type' => 2,
        'description' => 'Доступ к созданию пользователей'
    ],
    'oModifyUser' => [
        'type' => 2,
        'description' => 'Доступ к обновлению данных пользователей'
    ],
    'oBlockUser' => [
        'type' => 2,
        'description' => 'Доступ к блокировке пользователей'
    ],
    'oUnblockUser' => [
        'type' => 2,
        'description' => 'Доступ к разблокировке пользователей'
    ],
    'rDefaultUser' => [
        'type' => 1,
        'description' => 'Обычный пользователь системы',
        'children' => []
    ],
    'rAdmin' => [
        'type' => 1,
        'description' => 'Администратор системы',
        'children' => [
            'oAccessAdminPanel',
            'oAccessUserManagement',
            'oCreateUser',
            'oModifyUser',
            'oBlockUser',
            'oUnblockUser'
        ]
    ]
];