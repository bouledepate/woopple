<?php

namespace Woopple\Components\Widgets;

use hail812\adminlte\widgets\Menu as AdminlteMenu;

class Menu extends AdminlteMenu
{
    public $options = [
        'class' => 'nav nav-pills nav-sidebar flex-column nav-legacy nav-compact',
        'data-widget' => 'treeview',
        'role' => 'menu',
        'data-accordion' => 'false'
    ];

    public function init(): void
    {
        $path = \Yii::getAlias('@wooppleApp') . '/config/navigation/sidebar.php';
        $this->items = require $path;
    }
}