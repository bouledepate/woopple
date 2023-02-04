<?php

$aliases = require dirname(__DIR__, 3) . '/common/config/aliases.php';

return [
    'controllerNamespace' => 'Woopple\Modules\Admin\Controllers',
    'layoutPath' => '@wooppleSource/Layouts',
    'viewPath' => '@wooppleSource/Modules/Admin/Views',
    'aliases' => $aliases
];