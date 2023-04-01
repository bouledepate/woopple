<?php

return [
    '/admin' => 'admin/default/index',
    '/admin/users' => 'admin/user/index',
    '/admin/users/create' => 'admin/user/create',
    '/admin/users/blacklist' => 'admin/blacklist/index',
    '/admin/users/blacklist/add' => 'admin/blacklist/block',
    '/admin/users/blacklist/remove' => 'admin/blacklist/unblock',
    '/admin/departments' => 'admin/department/index',
    '/admin/departments/add' => 'admin/department/add',
    '/admin/departments/remove/<id>' => 'admin/department/remove',
];