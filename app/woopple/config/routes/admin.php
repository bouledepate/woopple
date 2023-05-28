<?php

return [
    '/admin' => 'admin/default/index',
    '/admin/users' => 'admin/user/index',
    '/admin/users/create' => 'admin/user/create',
    '/admin/blacklist' => 'admin/blacklist/index',
    '/admin/blacklist/add' => 'admin/blacklist/block',
    '/admin/blacklist/remove' => 'admin/blacklist/unblock',
    '/admin/security' => 'admin/user/security',
    '/admin/departments' => 'admin/department/index',
    '/admin/departments/add' => 'admin/department/add',
    '/admin/departments/modify/<id>' => 'admin/department/modify',
    '/admin/departments/remove/<id>' => 'admin/department/remove',
];