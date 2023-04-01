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
            'oUnblockUser',
        ]
    ],
    'rSecurityManager' => [
        'type' => 1,
        'description' => 'Сотрудник безопасности',
        'children' => [
            'oAccessAdminPanel',
            'oAccessSecurityControl',
            'oAccessBlacklistControl',
            'oBlockUser',
            'oUnblockUser',
            'oAccessRestorePasswordControl',
            'oRestorePassword',
        ]
    ],
    'oAccessSecurityControl' => [
        'type' => 2,
        'description' => 'Доступ в раздел управления безопасностью'
    ],
    'oAccessRestorePasswordControl' => [
        'type' => 2,
        'description' => 'Доступ в раздел просмотра заявок на сброс пароля'
    ],
    'oRestorePassword' => [
        'type' => 2,
        'description' => 'Доступ к сбросу пароля'
    ],
    'oAccessBlacklistControl' => [
        'type' => 2,
        'description' => 'Доступ к разделу блокировки пользователей'
    ],
    'oAccessDepartmentControl' => [
        'type' => 2,
        'description' => 'Доступ к управлению отделами'
    ],
    'oCreateDepartment' => [
        'type' => 2,
        'description' => 'Создание нового отдела'
    ],
    'oDeleteDepartment' => [
        'type' => 2,
        'description' => 'Удаление существующего отдела'
    ],
    'oModifyDepartment' => [
        'type' => 2,
        'description' => 'Изменение существующего отдела'
    ],
];