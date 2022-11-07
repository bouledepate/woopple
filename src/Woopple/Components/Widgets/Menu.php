<?php

namespace Woopple\Components\Widgets;

use hail812\adminlte\widgets\Menu as AdminlteMenu;

class Menu extends AdminlteMenu
{
    public $options = [
        'class' => 'nav nav-pills nav-sidebar flex-column nav-legacy nav-compact nav-collapse-hide-child',
        'data-widget' => 'treeview',
        'role' => 'menu',
        'data-accordion' => 'false'
    ];
}