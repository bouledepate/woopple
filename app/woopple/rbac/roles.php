<?php

return [
    'rDefaultUser' => [
        'type' => 1,
        'description' => 'Пользователь',
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
            'oAccessDepartmentControl',
            'oCreateDepartment',
            'oModifyDepartment',
            'oDeleteDepartment'
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
    'rHR' => [
        'type' => 1,
        'description' => 'Сотрудник HR',
        'children' => [
            'oAccessHumanResourceSection',
            'oAccessHREmployersSection',
            'oAccessHRBeginnersSection',
            'oFillProfile',
            'oViewPersonalSection',
            'oViewStructureSection',
            'oViewDepartmentList',
            'oViewTeamList'
        ]
    ]
];