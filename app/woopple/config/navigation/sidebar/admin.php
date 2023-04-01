<?php

use Core\Enums\Permission;

return [
    [
        'label' => Yii::t('navigation', 'admin-users'),
        'icon' => 'users',
        'url' => ['/admin/users'],
        'access' => Permission::ACCESS_USER_MANAGEMENT->value
    ],
    [
        'label' => Yii::t('navigation', 'admin-security'),
        'icon' => 'lock',
        'access' => Permission::ACCESS_SECURITY_CONTROL->value,
        'items' => [
            [
                'label' => Yii::t('navigation', 'admin-sec-restore'),
                'url' => ['/admin/security'],
                'iconStyle' => 'fas',
                'icon' => 'key',
                'access' => Permission::ACCESS_RESTORE_PASS_CONTROL->value
            ],
            [
                'label' => Yii::t('navigation', 'admin-sec-blacklist'),
                'url' => ['/admin/users/blacklist'],
                'iconStyle' => 'fas',
                'icon' => 'clipboard-list',
                'access' => Permission::ACCESS_BLACKLIST_CONTROL->value
            ]
        ]
    ],
    [
        'label' => Yii::t('navigation', 'admin-departments'),
        'icon' => 'layer-group',
        'url' => ['/admin/departments'],
        'access' => Permission::ACCESS_DEPARTMENT_CONTROL->value
    ]
];