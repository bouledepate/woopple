<?php

use Core\Enums\Permission;

return [
    [
        'label' => Yii::t('navigation', 'admin-users'),
        'icon' => 'users',
        'url' => ['/admin/users'],
        'access' => Permission::ACCESS_USER_MANAGEMENT->value
    ]
];